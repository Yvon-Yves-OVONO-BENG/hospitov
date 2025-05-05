<?php

namespace App\Controller\TypeChambre;

use App\Entity\TypeChambre;
use App\Form\TypeChambreType;
use App\Service\StrService;
use App\Repository\TypeChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/typeChambre')]
class AjouterTypeChambreController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected TypeChambreRepository $typeChambreRepository
    )
    {}

    #[Route('/ajouter-type-chambre', name: 'ajouter_type_chambre')]
    public function ajouterTypeChambre(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance typeChambre
        $typeChambre = new TypeChambre;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(TypeChambreType::class, $typeChambre);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la typeChambre
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //////j'extrait la dernière typeChambre de la table
        $derniereTypeChambre = $this->typeChambreRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereTypeChambre) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereTypeChambre[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $typeChambre->setTypeChambre($this->strService->strToUpper($typeChambre->getTypeChambre()))
            ->setSupprime(0)
            ->setEnService(1)
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($typeChambre);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Type Chambre ajouté avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $typeChambre = new TypeChambre;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(TypeChambreType::class, $typeChambre);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('typeChambre/ajouterTypeChambre.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formTypeChambre' => $form->createView(),
            'dossier' => $this->translator->trans("Type chambre"),
            'route' => $this->translator->trans("Ajout d'un type chambre"),
        ]);
    }
}

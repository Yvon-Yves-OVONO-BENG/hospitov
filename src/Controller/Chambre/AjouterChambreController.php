<?php

namespace App\Controller\Chambre;

use App\Entity\ConstantsClass;
use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Service\StrService;
use App\Repository\ChambreRepository;
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
#[Route('/chambre')]
class AjouterChambreController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected ChambreRepository $produitRepository
    )
    {}

    #[Route('/ajouter-chambre', name: 'ajouter_chambre')]
    public function ajouterChambre(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance chambre
        $chambre = new Chambre;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(ChambreType::class, $chambre);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la chambre
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //////j'extrait la dernière chambre de la table
        $derniereChambre = $this->produitRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereChambre) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereChambre[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $chambre->setChambre($this->strService->strToUpper($chambre->getChambre()))
            ->setEnService(1)
            ->setSupprime(0)
            ->setNombreLitOccupe(0)
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($chambre);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Chambre ajoutée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $chambre = new Chambre;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(ChambreType::class, $chambre);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('chambre/ajouterChambre.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formChambre' => $form->createView(),
            'dossier' => $this->translator->trans("Chambre"),
            'route' => $this->translator->trans("Ajout d'une chambre"),
        ]);
    }
}

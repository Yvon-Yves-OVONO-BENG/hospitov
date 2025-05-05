<?php

namespace App\Controller\TypeChambre;

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
class ModifierTypeChambreController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected TypeChambreRepository $typeChambreRepository,
    )
    {}
    
    #[Route('/modifier-type-chambre/{slug}', name: 'modifier_type_chambre')]
    public function modifierTypeChambre(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la typeChambre dont je veux modifier
        $typeChambre = $this->typeChambreRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
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

        //je récupère la date de maintenant
        $now = new \DateTime('now');

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
            ->setSlug($slug.$id.md5(uniqid('', true)));
            
            #je prepare ma requete
            $this->em->persist($typeChambre);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Type Chambre modifié avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je redirige vers la liste des typeChambres
            return $this->redirectToRoute('liste_type_chambre', ['m' => 1 ]);
        }

        return $this->render('typeChambre/ajouterTypeChambre.html.twig', [
            'slug' => $slug,
            'typeChambre' => $typeChambre,
            'licence' => 1,
            'formTypeChambre' => $form->createView(),
            'dossier' => $this->translator->trans("Type chambre"),
            'route' => $this->translator->trans("Modification de : ").$typeChambre->getTypeChambre(),
        ]);
    }
}

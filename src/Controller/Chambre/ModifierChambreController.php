<?php

namespace App\Controller\Chambre;

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
class ModifierChambreController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected ChambreRepository $chambreRepository,
    )
    {}
    
    #[Route('/modifier-chambre/{slug}', name: 'modifier_chambre')]
    public function modifierChambre(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la chambre dont je veux modifier
        $chambre = $this->chambreRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
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

        //je récupère la date de maintenant
        $now = new \DateTime('now');

        //////j'extrait la dernière chambre de la table
        $derniereChambre = $this->chambreRepository->findBy([], ['id' => 'DESC'], 1, 0);

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
            ->setSlug($slug.$id.md5(uniqid('', true)));
            
            #je prepare ma requete
            $this->em->persist($chambre);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Chambre modifiée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je redirige vers la liste des chambres
            return $this->redirectToRoute('liste_chambre', ['m' => 1 ]);
        }

        return $this->render('chambre/ajouterChambre.html.twig', [
            'slug' => $slug,
            'chambre' => $chambre,
            'licence' => 1,
            'formChambre' => $form->createView(),
            'dossier' => $this->translator->trans("Chambre"),
            'route' => $this->translator->trans("Modification de la chambre : ").$chambre->getChambre(),
        ]);
    }
}

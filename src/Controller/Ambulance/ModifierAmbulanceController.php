<?php

namespace App\Controller\Ambulance;

use App\Form\AmbulanceType;
use App\Service\StrService;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use App\Repository\AmbulanceRepository;
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
#[Route('/ambulance')]
class ModifierAmbulanceController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected MarqueRepository $marqueRepository,
        protected ModeleRepository $modeleRepository,
        protected AmbulanceRepository $ambulanceRepository,
    )
    {}
    
    #[Route('/modifier-ambulance/{slug}', name: 'modifier_ambulance')]
    public function modifierAmbulance(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la ambulance dont je veux modifier
        $ambulance = $this->ambulanceRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(AmbulanceType::class, $ambulance);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la ambulance
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //je récupère la date de maintenant
        $now = new \DateTime('now');

        //////j'extrait la dernière ambulance de la table
        $derniereAmbulance = $this->ambulanceRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereAmbulance) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereAmbulance[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        #je récupère les marques
        $marques = $this->marqueRepository->findBy([],['marque' => 'ASC']);
        $modeles = $this->modeleRepository->findBy([],['modele' => 'ASC']);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $modele = $this->modeleRepository->find($request->request->get('idModele'));

            switch ($ambulance->getEtatAmbulance()->getEtatAmbulance()) 
            {
                case 'AU GARAGE':
                    $ambulance->setLibre(0)->setService(0);
                    break;

                case 'EN PANNE':
                    $ambulance->setLibre(0)->setService(0);
                    break;
                
                
            }

            $ambulance->setAmbulance($this->strService->strToUpper($ambulance->getAmbulance()))
            ->setModele($modele)
            ->setSupprime(0)
            ->setSlug($slug.$id.md5(uniqid('', true)));
            
            #je prepare ma requete
            $this->em->persist($ambulance);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Ambulance modifiée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je redirige vers la liste des ambulances
            return $this->redirectToRoute('liste_ambulance', ['m' => 1 ]);
        }

        return $this->render('ambulance/ajouterAmbulance.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'modeles' => $modeles,
            'marques' => $marques,
            'ambulance' => $ambulance,
            'formAmbulance' => $form->createView(),
            'dossier' => $this->translator->trans("Ambulance"),
            'route' => $this->translator->trans("Modification de l'ambulance : ").$ambulance->getAmbulance(),
        ]);
    }
}

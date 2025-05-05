<?php

namespace App\Controller\Ambulance;

use App\Entity\ConstantsClass;
use App\Entity\Ambulance;
use App\Form\AmbulanceType;
use App\Service\StrService;
use App\Repository\AmbulanceRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
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
class AjouterAmbulanceController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected MarqueRepository $marqueRepository,
        protected ModeleRepository $modeleRepository,
        protected AmbulanceRepository $produitRepository
    )
    {}

    #[Route('/ajouter-ambulance', name: 'ajouter_ambulance')]
    public function ajouterAmbulance(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance ambulance
        $ambulance = new Ambulance;

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

        //////j'extrait la dernière ambulance de la table
        $derniereAmbulance = $this->produitRepository->findBy([], ['id' => 'DESC'], 1, 0);

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

            $ambulance->setAmbulance($this->strService->strToUpper($ambulance->getAmbulance()))
            ->setModele($modele)
            ->setSupprime(0)
            ->setLibre(1)
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($ambulance);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Ambulance ajoutée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $ambulance = new Ambulance;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(AmbulanceType::class, $ambulance);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('ambulance/ajouterAmbulance.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'modeles' => $modeles,
            'marques' => $marques,
            'formAmbulance' => $form->createView(),
            'dossier' => $this->translator->trans("Ambulance"),
            'route' => $this->translator->trans("Ajout d'une ambulance"),
        ]);
    }
}

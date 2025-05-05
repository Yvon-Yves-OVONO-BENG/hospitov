<?php

namespace App\Controller\AttributionAmbulance;

use App\Entity\AttributionAmbulance;
use App\Form\AttributionAmbulanceType;
use App\Service\StrService;
use App\Repository\AttributionAmbulanceRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use DateTime;
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
#[Route('/attributionAmbulance')]
class AjouterAttributionAmbulanceController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected MarqueRepository $marqueRepository,
        protected ModeleRepository $modeleRepository,
        protected AttributionAmbulanceRepository $produitRepository
    )
    {}

    #[Route('/ajouter-attribution-ambulance', name: 'ajouter_attribution_ambulance')]
    public function ajouterAttributionAmbulance(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance attributionAmbulance
        $attributionAmbulance = new AttributionAmbulance;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(AttributionAmbulanceType::class, $attributionAmbulance);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la attributionAmbulance
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //////j'extrait la dernière attributionAmbulance de la table
        $derniereAttributionAmbulance = $this->produitRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereAttributionAmbulance) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereAttributionAmbulance[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $attributionAmbulance->setDateAttributionAt(new DateTime('now'))
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($attributionAmbulance);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Attribution Ambulance ajoutée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $attributionAmbulance = new AttributionAmbulance;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(AttributionAmbulanceType::class, $attributionAmbulance);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('attributionAmbulance/ajouterAttributionAmbulance.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formAttributionAmbulance' => $form->createView(),
            'dossier' => $this->translator->trans("Attribution ambulance"),
            'route' => $this->translator->trans("Ajout attribution ambulance"),
        ]);
    }
}

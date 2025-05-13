<?php

namespace App\Controller\Batiment;

use App\Entity\ConstantsClass;
use App\Entity\Batiment;
use App\Form\BatimentType;
use App\Service\StrService;
use App\Repository\BatimentRepository;
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
#[Route('/batiment')]
class AjouterBatimentController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private BatimentRepository $produitRepository
    )
    {}

    #[Route('/ajouter-batiment', name: 'ajouter_batiment')]
    public function ajouterBatiment(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance batiment
        $batiment = new Batiment;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(BatimentType::class, $batiment);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la batiment
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //////j'extrait la dernière batiment de la table
        $derniereBatiment = $this->produitRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereBatiment) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereBatiment[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $batiment->setBatiment($this->strService->strToUpper($batiment->getBatiment()))
            ->setSupprime(0)
            ->setEnService(1)
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($batiment);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Batiment ajouté avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $batiment = new Batiment;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(BatimentType::class, $batiment);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('batiment/ajouterBatiment.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formBatiment' => $form->createView(),
            'dossier' => $this->translator->trans("Bâtiment"),
            'route' => $this->translator->trans("Ajout d'un bâtiment"),
        ]);
    }
}

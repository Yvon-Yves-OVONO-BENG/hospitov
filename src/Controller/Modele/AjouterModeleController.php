<?php

namespace App\Controller\Modele;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Service\StrService;
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
#[Route('/modele')]
class AjouterModeleController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected ModeleRepository $produitRepository
    )
    {}

    #[Route('/ajouter-modele', name: 'ajouter_modele')]
    public function ajouterModele(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance modele
        $modele = new Modele;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(ModeleType::class, $modele);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        #je construis le code pour la reference de la modele
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $slug      = '';

        for ($i = 0; $i < 11; $i++) {
            $slug .= substr($characts, rand() % (strlen($characts)), 1);
        }

        //////j'extrait la dernière modele de la table
        $derniereModele = $this->produitRepository->findBy([], ['id' => 'DESC'], 1, 0);

        if ($derniereModele) 
        {
            /////je récupère l'id du dernier abonnement
            $id = $derniereModele[0]->getId();
        } 
        else 
        {
            $id = 1;
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $modele->setModele($this->strService->strToUpper($modele->getModele()))
            ->setSupprime(0)
            ->setSlug($slug.$id.md5(uniqid('', true)))
            ;

            #je prepare ma requete
            $this->em->persist($modele);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Modele ajouté avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je declare une nouvelle instance
            $modele = new Modele;

            #je lie mon formulaire à la nouvelle instance
            $form = $this->createForm(ModeleType::class, $modele);
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('modele/ajouterModele.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formModele' => $form->createView(),
            'dossier' => $this->translator->trans("Modèle"),
            'route' => $this->translator->trans("Ajout d'un modèle"),
        ]);
    }
}

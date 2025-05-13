<?php

namespace App\Controller\Modele;

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
class ModifierModeleController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ModeleRepository $modeleRepository,
    )
    {}
    
    #[Route('/modifier-modele/{slug}', name: 'modifier_modele')]
    public function modifierModele(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la modele dont je veux modifier
        $modele = $this->modeleRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
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

        //je récupère la date de maintenant
        $now = new \DateTime('now');

        //////j'extrait la dernière modele de la table
        $derniereModele = $this->modeleRepository->findBy([], ['id' => 'DESC'], 1, 0);

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
            ->setSlug($slug.$id.md5(uniqid('', true)));
            
            #je prepare ma requete
            $this->em->persist($modele);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Modele modifiée avec succès !'));
            
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je redirige vers la liste des modeles
            return $this->redirectToRoute('liste_modele', ['m' => 1 ]);
        }

        return $this->render('modele/ajouterModele.html.twig', [
            'slug' => $slug,
            'modele' => $modele,
            'licence' => 1,
            'formModele' => $form->createView(),
            'dossier' => $this->translator->trans("Modèle"),
            'route' => $this->translator->trans("Modification du modèle : ").$modele->getModele(),
        ]);
    }
}

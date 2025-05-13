<?php

namespace App\Controller\Echographie;

use App\Form\EchographieType;
use App\Service\StrService;
use App\Repository\ProduitRepository;
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
#[Route('/echographie')]
class ModifierEchographieController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository,
    )
    {}

    #[Route('/modifier-echographie/{slug}', name: 'modifier_echographie')]
    public function modifierEchographie(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère l'echographie à modifier
        $echographie = $this->produitRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(EchographieType::class, $echographie);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        #je fabrique mon slug
        $characts    = 'abcdefghijklmnopqrstuvwxyz#{};()';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ#{};()';	
        $characts   .= '1234567890'; 
        $slug      = ''; 

        for($i=0;$i < 11;$i++) 
        { 
            $slug .= substr($characts,rand()%(strlen($characts)),1); 
        }

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je met le nom de la echographie en CAPITAL LETTER
            $echographie->setLibelle($this->strService->strToUpper($echographie->getLibelle()))
                    ->setSlug($slug)
            ;

            # je prépare ma requête avec entityManager
            $this->em->persist($echographie);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Echographie mise à jour avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je retourne à la liste des echographies
            return $this->redirectToRoute('afficher_echographie', [ 'm' => 1 ]);
        }

        # j'affiche mon formulaire avec twig
        return $this->render('echographie/ajouterEchographie.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'echographie' => $echographie,
            'formEchographie' => $form->createView(),
            'dossier' => $this->translator->trans("Echographie"),
            'route' => $this->translator->trans("Modification de : ").$echographie->getLibelle(),
        ]);
    }
}

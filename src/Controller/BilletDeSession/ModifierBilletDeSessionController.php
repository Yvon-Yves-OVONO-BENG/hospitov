<?php

namespace App\Controller\BilletDeSession;

use App\Form\BilletDeSessionType;
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
#[Route('/billetDeSession')]
class ModifierBilletDeSessionController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected ProduitRepository $produitRepository,
    )
    {}

    #[Route('/modifier-billetDeSession/{slug}', name: 'modifier_billetDeSession')]
    public function modifierBilletDeSession(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère l'billetDeSession à modifier
        $billetDeSession = $this->produitRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(BilletDeSessionType::class, $billetDeSession);

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
            #je met le nom de la billetDeSession en CAPITAL LETTER
            $billetDeSession->setLibelle($this->strService->strToUpper($billetDeSession->getLibelle()))
                    ->setSlug($slug)
            ;

            # je prépare ma requête avec entityManager
            $this->em->persist($billetDeSession);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Billet de session mise à jour avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je retourne à la liste des billetDeSessions
            return $this->redirectToRoute('afficher_billet_de_session', [ 'm' => 1 ]);
        }

        # j'affiche mon formulaire avec twig
        return $this->render('billetDeSession/ajouterBilletDeSession.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'billetDeSession' => $billetDeSession,
            'formBilletDeSession' => $form->createView(),
            'dossier' => $this->translator->trans("Billet de session"),
            'route' => $this->translator->trans("Modification du billet : ").$billetDeSession->getLibelle(),
        ]);
    }
}

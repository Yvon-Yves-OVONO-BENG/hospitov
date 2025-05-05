<?php

namespace App\Controller\BilletDeSession;

use App\Entity\Produit;
use App\Service\StrService;
use App\Entity\ConstantsClass;
use App\Form\BilletDeSessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('billetDeSession')]
class AjouterBilletDeSessionController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/ajouter-billet-de-session', name: 'ajouter_billet_de_session')]
    public function ajouterBilletDeSession(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une billetDeSession
        $billetDeSession = new Produit;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(BilletDeSessionType::class, $billetDeSession);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('envoieMessage')->getValue();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('envoieMessage', $csrfTokenFormulaire))) 
            {
                #je fabrique mon slug
                $characts    = 'abcdefghijklmnopqrstuvwxyz#{};()';
                $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ#{};()';	
                $characts   .= '1234567890'; 
                $slug      = ''; 
        
                for($i=0;$i < 11;$i++) 
                { 
                    $slug .= substr($characts,rand()%(strlen($characts)),1); 
                }

                #je met le nom de la billetDeSession en CAPITAL LETTER
                $billetDeSession->setLibelle($this->strService->strToUpper($billetDeSession->getLibelle()))
                        ->setSlug($slug)
                        ->setBilletDeSession(1)
                        ->setCarnet(0)
                        ->setEchographie(0)
                        ->setExamen(0)
                        ->setKit(0)
                        ->setRadio(0)
                        ->setSupprime(0)
                        ->setRetire(0)
                        ->setPhoto(ConstantsClass::NOM_PRODUIT)
                ;
                
                # je prépare ma requête avec entityManager
                $this->em->persist($billetDeSession);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Billet de session ajouté avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je déclare une nouvelle instace d'une billetDeSession
                $billetDeSession = new Produit;

                #je crée mon formulaire et je le lie à mon instance
                $form = $this->createForm(BilletDeSessionType::class, $billetDeSession);
            }
            else 
            {
                /**
                 * @var User
                 */
                $user = $this->getUser();
                $user->setEtat(1);

                $this->em->persist($user);
                $this->em->flush();

                return $this->redirectToRoute('accueil', ['b' => 1 ]);

            }
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('billetDeSession/ajouterBilletDeSession.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'csrfToken' => $csrfToken,
            'formBilletDeSession' => $form->createView(),
            'dossier' => $this->translator->trans("Billet de session"),
            'route' => $this->translator->trans("Ajout d'un billet de session"),
        ]);
    }
}

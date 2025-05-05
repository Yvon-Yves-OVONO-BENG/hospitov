<?php

namespace App\Controller\Retenue;

use App\Entity\Retenue;
use App\Form\RetenueType;
use App\Service\StrService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('retenue')]
class AjouterRetenueController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/ajouter-retenue', name: 'ajouter_retenue')]
    public function ajouterRetenue(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('misAjour', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une retenue
        $retenue = new Retenue;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(RetenueType::class, $retenue);

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
                #je met le nom de la retenue en CAPITAL LETTER
                $retenue->setRetenue($this->strService->strToUpper($retenue->getRetenue()))
                        ->setSlug(uniqid("", true))
                        ->setActif(1)
                ;
                
                # je prépare ma requête avec entityManager
                $this->em->persist($retenue);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Retenue ajoutée avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je déclare une nouvelle instace d'une retenue
                $retenue = new Retenue;

                #je crée mon formulaire et je le lie à mon instance
                $form = $this->createForm(RetenueType::class, $retenue);
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

        return $this->render('retenue/ajouterRetenue.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'csrfToken' => $csrfToken,
            'formRetenue' => $form->createView(),
            'route' => $this->translator->trans("Les retenues"),
            'dossier' => $this->translator->trans("Ajout d'une retenue"),
        ]);
    }
}

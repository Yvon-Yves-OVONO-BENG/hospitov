<?php

namespace App\Controller\Lit;

use App\Form\LitType;
use App\Service\StrService;
use App\Repository\LitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/lit')]
class ModifierLitController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private LitRepository $litRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}
    
    #[Route('/modifier-lit/{slug}', name: 'modifier_lit')]
    public function modifierLit(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la lit dont je veux modifier
        $lit = $this->litRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(LitType::class, $lit);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('modificationLit')->getValue();

        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('modificationLit', $csrfTokenFormulaire))) 
            {

                $lit->setNumeroLit($this->strService->strToUpper($lit->getNumeroLit()))
                    ->setSupprime(0)
                    ->setEnService(1)
                    ->setEtat(0)
                    ->setSlug(uniqid('', true));
                
                #je prepare ma requete
                $this->em->persist($lit);

                #j'exécute ma requête
                $this->em->flush();

                #j'affiche le message de confirmation
                $this->addFlash('info', $this->translator->trans('Lit modifié avec succès !'));
                
                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('misAjour', 1);
                
                #je redirige vers la liste des lits
                return $this->redirectToRoute('liste_lit', ['m' => 1 ]);
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

                return $this->redirectToRoute('app_logout');
            }
        }

        return $this->render('lit/ajouterLit.html.twig', [
            'slug' => $slug,
            'lit' => $lit,
            'licence' => 1,
            'csrfToken' => $csrfToken,
            'formLit' => $form->createView(),
            'dossier' => $this->translator->trans("Lit"),
            'route' => $this->translator->trans("Modification du lit : ").$lit->getNumeroLit(),
        ]);
    }
}

<?php

namespace App\Controller\Lit;

use App\Entity\Lit;
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
class AjouterLitController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private LitRepository $litRepository, 
        private CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/ajouter-lit', name: 'ajouter_lit')]
    public function ajouterLit(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #j'initialise le slug
        $slug = 0;

        #je céclare une nouvelle instance lit
        $lit = new Lit;

        #je lie mon formulaire à ma nouvelle instance
        $form = $this->createForm(LitType::class, $lit);

        #je demande à mon formulaire de gueter tout ce qui est dans le POST
        $form->handleRequest($request);

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('ajoutLit')->getValue();

        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('ajoutLit', $csrfTokenFormulaire))) 
            {

                $lit->setNumeroLit($this->strService->strToUpper($lit->getNumeroLit()))
                ->setSupprime(0)
                ->setEnService(1)
                ->setDisponible(1)
                ->setSlug(uniqid('', true));

                #je prepare ma requete
                $this->em->persist($lit);

                #j'exécute ma requête
                $this->em->flush();

                #j'affiche le message de confirmation
                $this->addFlash('info', $this->translator->trans('Lit ajouté avec succès !'));
                
                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je declare une nouvelle instance
                $lit = new Lit;

                #je lie mon formulaire à la nouvelle instance
                $form = $this->createForm(LitType::class, $lit);
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

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('lit/ajouterLit.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'csrfToken' => $csrfToken,
            'formLit' => $form->createView(),
            'dossier' => $this->translator->trans("Lit"),
            'route' => $this->translator->trans("Ajout d'un lit"),
        ]);
    }
}

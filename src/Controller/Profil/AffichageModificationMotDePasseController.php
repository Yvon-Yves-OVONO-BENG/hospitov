<?php

namespace App\Controller\Profil;

use App\Service\SessionService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/profil')]
class AffichageModificationMotDePasseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected SessionService $sessionService,
        protected TranslatorInterface $translator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected UserPasswordHasherInterface $userPasswordHasher,
    )
    {}

    #[Route('/affichage-modification-mot-de-passe', name: 'affichage_modification_mot_de_passe')]
    public function modifierMotDePasse(Request $request): Response
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
        
        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('change_password')->getValue();

        return $this->render('profil/affichage_modification_mot_de_passe.html.twig', [
            'licence' => 1,
            'motDePasse' => 1,
            'csrfToken' => $csrfToken,
            'dossier' => $this->translator->trans("Profil"),
            'route' => $this->translator->trans("Modification de mon mot de passe"),
        ]);
    }
}

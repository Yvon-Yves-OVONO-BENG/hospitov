<?php

namespace App\Controller\Profil;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Doctrine\ORM\EntityManagerInterface;

class ModificationMotDePasseController extends AbstractController
{
    #[Route('/modification-mot-de-passe', name: 'modification_mot_de_passe', methods: ['POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        CsrfTokenManagerInterface $csrfTokenManager,
        EntityManagerInterface $em,
        UserRepository $userRepository,
    ): JsonResponse 
    {
        /**
         * @var User
         */
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        $user = $userRepository->find($user->getId());

        // Récupération des données envoyées en AJAX
        $actuelMotDePasse = $request->request->get('actuelMotDePasse');
        $nouveauMotDePasse = $request->request->get('nouveauMotDePasse');
        $confirmerMotDePasse = $request->request->get('confirmerMotDePasse');
        $csrfToken = $request->request->get('_csrf_token');

        // Vérifier la validité du token CSRF
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('change_password', $csrfToken))) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Jeton CSRF invalide.'
            ]);
        }

        // Vérifier les champs requis
        if (!$actuelMotDePasse || !$nouveauMotDePasse || !$confirmerMotDePasse) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Tous les champs sont obligatoires.'
            ]);
        }

        if ($nouveauMotDePasse !== $confirmerMotDePasse) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Les mots de passe ne correspondent pas.'
            ]);
        }

        // Vérifier que le mot de passe actuel est correct
        if (!$passwordHasher->isPasswordValid($user, $actuelMotDePasse)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Mot de passe actuel incorrect.'
            ]);
        }

        // Hasher et enregistrer le nouveau mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $nouveauMotDePasse);
        $user->setPassword($hashedPassword);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Mot de passe modifié avec succès.'
        ]);
    }
}
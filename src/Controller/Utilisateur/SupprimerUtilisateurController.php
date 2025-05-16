<?php

namespace App\Controller\Utilisateur;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SupprimerUtilisateurController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
    )
    {}

    #[Route('/supprimer-utilisateur/{slug}', name: 'supprimer_utilisateur')]
    public function index(Request $request, $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        $utilisateur = $this->userRepository->findOneBy(['slug' => $slug ]);
        
        $utilisateur->setSupprime(1);
        #je prépare ma requête à la suppression
        $this->em->persist($utilisateur);

        #j'exécute ma requête
        $this->em->flush();

        $this->addFlash('info', 'Personnel supprimé avec succès !');
                
        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);

        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_utilisateur', ['s' => 1]);
    }
}

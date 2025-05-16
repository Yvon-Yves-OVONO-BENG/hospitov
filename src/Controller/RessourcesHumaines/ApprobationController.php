<?php

namespace App\Controller\RessourcesHumaines;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class ApprobationController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
    )
    {}

    #[Route('/approbation', name: 'approbation')]
    public function approbation(Request $request): Response
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
        $maSession->set('miseAjour', null);

        #je récupère le personnel dont le statut est actif
        $personnels = $this->userRepository->getUserApprobation();

        return $this->render('ressourcesHumaines/approbation.html.twig', [
            'personnels' => $personnels,
            'route' => $this->translator->trans("Approbation"),
            'dossier' => $this->translator->trans("Ressources Humaines"),
        ]);
    }
}

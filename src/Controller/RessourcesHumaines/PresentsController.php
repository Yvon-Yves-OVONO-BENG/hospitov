<?php

namespace App\Controller\RessourcesHumaines;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class PresentsController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {}
    
    #[Route('/presents', name: 'presents')]
    public function presents(): Response
    {
        return $this->render('ressourcesHumaines/presents.html.twig', [
            'dossier' => $this->translator->trans("Ressources Humaines"),
            'route' => $this->translator->trans("Présents"),
            
        ]);
    }
}

<?php

namespace App\Controller\RessourcesHumaines;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class EnCongesController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {}
    
    #[Route('/en-conges', name: 'en_conges')]
    public function enConges(): Response
    {
        return $this->render('ressourcesHumaines/enConges.html.twig', [
            'dossier' => $this->translator->trans("Ressources Humaines"),
            'route' => $this->translator->trans("En congés"),
            
        ]);
    }
}

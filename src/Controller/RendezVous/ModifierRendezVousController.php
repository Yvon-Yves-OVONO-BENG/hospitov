<?php

namespace App\Controller\RendezVous;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('rendezVous')]
class ModifierRendezVousController extends AbstractController
{
    #[Route('/modifier-rendez-vous/{slug}', name: 'modifier_rendez_vous')]
    public function ModifierRendezVous(): Response
    {
        return $this->render('rendezVous/modifier_rendez_vous.html.twig', [
            'controller_name' => 'ModifierRendezVousController',
        ]);
    }
}

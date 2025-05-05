<?php

namespace App\Controller\RendezVous;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('rendezVous')]
class DetailsRendezVousController extends AbstractController
{
    #[Route('/details-rendez-vous/{slug}', name: 'details_rendez_vous')]
    public function DetailsRendezVous(): Response
    {
        return $this->render('rendezVous/details_rendez_vous.html.twig', [
            'controller_name' => 'DetailsRendezVousController',
        ]);
    }
}

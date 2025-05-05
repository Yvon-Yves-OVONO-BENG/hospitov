<?php

namespace App\Controller\RendezVous;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('rendezVous')]
class SupprimerRendezVousController extends AbstractController
{
    #[Route('/supprimer-rendez-vous/{slug}', name: 'supprimer_rendez_vous')]
    public function SupprimerRendezVous(): Response
    {
        return $this->render('rendezVous/supprimer_rendez_vous.html.twig', [
            
        ]);
    }
}

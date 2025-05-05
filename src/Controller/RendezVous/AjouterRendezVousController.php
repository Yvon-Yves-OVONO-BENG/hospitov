<?php

namespace App\Controller\RendezVous;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('rendezVous')]
class AjouterRendezVousController extends AbstractController
{
    #[Route('/ajouter-rendez-vous', name: 'ajouter_rendez_vous')]
    public function index(): Response
    {
        return $this->render('rendezVous/ajouter_rendez_vous.html.twig', [
            
        ]);
    }
}

<?php

namespace App\Controller\RendezVous;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('rendezVous')]
class ListeRendezVousController extends AbstractController
{
    #[Route('/liste-rendez-vous', name: 'liste_rendez_vous')]
    public function ListeRendezVous(): Response
    {
        return $this->render('rendezVous/liste_rendez_vous.html.twig', [
            'controller_name' => 'ListeRendezVousController',
        ]);
    }
}

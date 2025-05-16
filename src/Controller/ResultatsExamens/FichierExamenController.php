<?php

namespace App\Controller\ResultatsExamens;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResultatExamenRepository;
use App\Service\FichierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class FichierExamenController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FichierService $fichierService,
        private ResultatExamenRepository $resultatExamenRepository,
    )
    {}

    #[Route('/fichier-examen/{slug}', name: 'fichier_examen')]
    public function fichierExamen(Request $request, $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$this->getUser()) 
        {
            return $this->redirectToRoute('app_logout');
        }

        return $this->fichierService->telechargerFichieResultat($slug);
        
    }
}

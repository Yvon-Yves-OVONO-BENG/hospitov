<?php

namespace App\Controller\ResultatsExamens;

use App\Entity\FichierResultatExamen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;

#[IsGranted('ROLE_USER')]
class SupprimerFichierExamenController extends AbstractController
{
    #[Route('/supprimer-fichier-examen/{id}', name: 'supprimer_fichier_examen', methods: ['POST', 'DELETE'])]
    public function SupprimerFichierExamen(Request $request, FichierResultatExamen $fichierResultatExamen, EntityManagerInterface $em): Response
    {
       if (!$this->getUser()) 
        {
            return new JsonResponse(['message' => 'Accès réfusé'], 403);
        }
        
        if (!$this->isCsrfTokenValid('delete'.$fichierResultatExamen->getId(), $request->request->get('_token'))) 
        {
            return new Response('Token CSRF invalide', 400);
        }
        try
        {
            #suppression physique du fichier 
            $filesystem = new Filesystem();
            $nomFichier = $fichierResultatExamen->getFichierResultat();
            
            $cheminComplet = '../../../public/fichiersExamens/'.$nomFichier;

            if ($filesystem->exists($cheminComplet)) 
            {
                $filesystem->remove($cheminComplet);
            }

            #suppression de la base de données 
            $em->remove($fichierResultatExamen);
            $em->flush();

            return new Response(null, 204);
        }
        catch(\Exception $e)
        {
            return new JsonResponse(['message' => 'Erreur lors de la suppression:', 'erreur' => $e->getMessage()], 500);
        }
        
    }
}

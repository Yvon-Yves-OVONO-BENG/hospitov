<?php

namespace App\Controller\ResultatsExamens;

use App\Entity\FichierResultatExamen;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResultatExamenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class EnvoieUnFichierController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ResultatExamenRepository $resultatExamenRepository,
    )
    {}

    #[Route('/envoie-un-fichier', name: 'envoie_un_fichier', methods: ['POST'])]
    public function envoieUnFichier(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        if ($request->headers->get('X-CSRF-TOKEN') != $maSession->get('csrfToken')) 
        {
            return new JsonResponse(['error' => 'Token CSRF invalide.'], 403);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if (!$file) 
        {
            return new JsonResponse(['error' => 'Aucun fichier reçu.'], 400);
        }

        // Validation sécurité
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        $mime = $file->getMimeType();
        $ext = $file->getClientOriginalExtension();

        if (!in_array($mime, $allowedMimeTypes) || !in_array($ext, $allowedExtensions)) 
        {
            return new JsonResponse(['error' => 'Type de fichier non autorisé.'], 415);
        }

        $filename = uniqid() . '-' . $file->getClientOriginalName();

        $file->move($this->getParameter('uploads_directory'), $filename);

        #je récupère le résultat examen
        $slug = $request->headers->get('slugResultatExamen');
        
        #je déclare une nouvelle instace d'un resultat examen
        $resultatExamen = $this->resultatExamenRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #un nouveau fichier
        $fichierResultatExamen = new FichierResultatExamen;

        #je met le nom de la retenue en CAPITAL LETTER
        $resultatExamen->setRealise(1)
        ->setDateResultatAt(new DateTime('today'))
        ->setHeureResultatAt(new DateTime('now'))
        ->setLaborantin($this->getUser())
        ;

        $fichierResultatExamen->setResultatExamen($resultatExamen)
        ->setFichierResultat($filename)
        ->setNomFichier($file->getClientOriginalName())
        ->setUpdatedAt(new DateTime('today'))
        ;
        
        # je prépare ma requête avec entityManager
        $this->em->persist($resultatExamen);
        $this->em->persist($fichierResultatExamen);

        #j'exécutebma requête
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}

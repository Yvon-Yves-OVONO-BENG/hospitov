<?php

namespace App\Controller\ResultatsExamens;

use App\Entity\FichierResultatExamen;
use App\Form\ResultatExamenType;
use DateTime;
use App\Service\StrService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResultatExamenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class EnvoiePlusieursFichiersController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ResultatExamenRepository $resultatExamenRepository,
    )
    {}

    #[Route('/envoie-plusieurs-fichiers', name: 'envoie_plusieurs_fichier', methods: ['POST'])]
    public function envoieUnFichier(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        if ($request->headers->get('X-CSRF-TOKEN') != $maSession->get('csrfToken')) 
        {
            return new JsonResponse(['error' => 'Token CSRF invalide.'], 403);
        }

        /** @var UploadedFile[] $files */
        $files = $request->files->all('files');
        if (empty($files)) 
        {
            return new JsonResponse(['error' => 'Aucun fichier reçu.'], 400);
        }


        #je récupère le résultat examen
        $slug = $request->headers->get('slugResultatExamen');
        
        #je déclare une nouvelle instace d'un resultat examen
        $resultatExamen = $this->resultatExamenRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je met le nom de la retenue en CAPITAL LETTER
        $resultatExamen->setRealise(1)
        ->setDateResultatAt(new DateTime('today'))
        ->setHeureResultatAt(new DateTime('now'))
        ->setLaborantin($this->getUser())
        ;

        # je prépare ma requête avec entityManager
        $this->em->persist($resultatExamen);

        foreach ($files as $file) 
        {
            // Validation sécurité
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

            $mime = $file->getMimeType();
            $ext = $file->getClientOriginalExtension();

            if (!in_array($mime, $allowedMimeTypes) || !in_array($ext, $allowedExtensions)) 
            {
                return new JsonResponse(['error' => 'Type de fichier non autorisé.'], 415);
            }

            if ($file instanceof UploadedFile) 
            {
                $filename = uniqid() . '-' . $file->getClientOriginalName();
                $file->move($this->getParameter('uploads_directory'), $filename);

                #un nouveau fichier
                $fichierResultatExamen = new FichierResultatExamen;

                $fichierResultatExamen->setResultatExamen($resultatExamen)
                ->setFichierResultat($filename)
                ->setNomFichier(str_replace(' ', '_', $file->getClientOriginalName()))
                ->setUpdatedAt(new DateTime('today'))
                ;

                $this->em->persist($fichierResultatExamen);
            }
        }

        #j'exécutebma requête
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}

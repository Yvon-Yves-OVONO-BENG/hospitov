<?php

namespace App\Controller\Patient;

use App\Form\PatientType;
use App\Service\StrService;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/patient')]
class DetailsPatientController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private PatientRepository $patientRepository,
    )
    {}

    #[Route('/details-patient/{slug}', name: 'details_patient')]
    public function detailsPatient(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        #je récupère la patient à modifier
        $patient = $this->patientRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        # j'affiche mon formulaire avec twig
        return $this->render('patient/detailsPatient.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'patient' => $patient,
            'dossier' => $this->translator->trans("Patient"),
            'route' => $this->translator->trans("Détails de ").$patient->getNom(),
        ]);
    }
}

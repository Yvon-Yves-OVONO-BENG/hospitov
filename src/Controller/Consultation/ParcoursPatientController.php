<?php

namespace App\Controller\Consultation;

use App\Repository\ConsultationRepository;
use App\Service\StrService;
use App\Repository\UserRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/consultation')]
class ParcoursPatientController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected PatientRepository $patientRepository,
        protected ConsultationRepository $consultationRepository,
    )
    {}

    #[Route('/parcours-patient/{slug}', name: 'parcours_patient')]
    public function parcoursPatient(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère le patient 
        $patient = $this->patientRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je récupère ses consultations
        $consultations = $this->consultationRepository->findBy([
            'patient' => $patient
        ], ['dateConsultationAt' => 'DESC']);

        # j'affiche mon formulaire avec twig
        return $this->render('consultation/parcoursPatient.html.twig', [
            'licence' => 1,
            'patient' => $patient,
            'consultations' => $consultations,
        ]);
    }
}

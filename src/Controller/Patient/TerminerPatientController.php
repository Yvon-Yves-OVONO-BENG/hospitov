<?php

namespace App\Controller\Patient;

use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/patient')]
class TerminerPatientController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private PatientRepository $patientRepository
    )
    {}

    #[Route('/terminer-patient', name: 'terminer_patient', methods: 'POST')]
    public function terminerPatient(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        $patientId = (int)$request->request->get('patient_id');
        
        $patient = $this->patientRepository->find($patientId);
        
        if ($patient->isTermine() == 0) 
        {
            $patient->setTermine(1);
        } else {
            $patient->setTermine(0);
        }
        
        
        #je prépare ma requête à la suppression
        $this->em->persist($patient);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'termine' => $patient->isTermine() ]);
    }
}

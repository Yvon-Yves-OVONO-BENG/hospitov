<?php

namespace App\Controller\Patient;

use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/patient')]
class RecuperePatientController extends AbstractController
{
    public function __construct(
        private PatientRepository $patientRepository,
    )
    {}

    #[Route('/recupere-patient/{id}', name: 'recupere_patient', methods: 'GET')]
    public function recuperePatient(Request $request, int $id): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère la patient dont je veux le contact
        $patient = $this->patientRepository->find($id);

        if (!$patient) 
        {
            return new JsonResponse(['error' => 'Patient non trouvé' ], 404);
        }

        # j'envoie le contact au formulaire
        return new JsonResponse([
            'contact' => $patient->getTelephone()
        ]);
    }
}

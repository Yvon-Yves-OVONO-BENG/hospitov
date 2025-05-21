<?php

namespace App\Controller\Patient;

use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/patient')]
class PatientParPeriodeController extends AbstractController
{
    #[Route('/patient-par-periode', name: 'patient_par_periode')]
    public function PatientParPeriode(Request $request, PatientRepository $patientRepository): Response
    {
        $dateDebut = $request->query->get('date_debut');
        $dateFin = $request->query->get('date_fin');

        $start = new \DateTime($dateDebut);
        $end = new \DateTime($dateFin);

        $patients = $patientRepository->createQueryBuilder('p')
            ->where('p.dateEnregistrementAt BETWEEN :start AND :end')
            ->setParameter('start', $start->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($patients as $p) {
            $data[] = [
                'code' => $p->getCode(),
                'nom' => $p->getNom(),
                'genre' => $p->getGenre() ? $p->getGenre()->getGenre() : '',
                'telephone' => $p->getTelephone(),
                'adresse' => $p->getAdresse(),
                'edit_url' => $this->generateUrl('modifier_patient', ['slug' => $p->getSlug()]),
                'detail_url' => $this->generateUrl('details_patient', ['slug' => $p->getSlug()])
            ];
        }

        return new JsonResponse($data);

    }
}

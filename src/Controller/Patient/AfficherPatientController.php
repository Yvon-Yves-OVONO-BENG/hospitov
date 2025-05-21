<?php

namespace App\Controller\Patient;

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
class AfficherPatientController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private PatientRepository $patientRepository,
        private EntityManagerInterface $em,
    )
    {}

    #[Route('/afficher-patient/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_patient')]
    public function afficherPatient(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        if ($a == 1 || $m == 0 || $s == 0) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la mise à jour
        if ($m == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', 1);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la suppression
        
        if ($s == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', 1);
            
        }
        
        #je récupère toutes les patients
        $patients = $this->patientRepository->findAll();
        
        // foreach ($patients as $patient) 
        // {
        //     $patient->setSlug(uniqid('', true));
        //     $this->em->persist($patient);

        // }
        $this->em->flush();
        #j'envoie mon tableau des categories à mon rendu twig pour affichage
        return $this->render('patient/afficherPatient.html.twig', [
            'licence' => 1,
            'patients' => $patients,
            'dossier' => $this->translator->trans("Patient"),
            'route' => $this->translator->trans("Liste des patients"),
        ]);
    }
}

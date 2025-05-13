<?php

namespace App\Controller\Consultation;

use App\Entity\Consultation;
use DateTime;
use App\Form\EnvoieFicherExamenType;
use App\Repository\PatientRepository;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('consultation')]

class ExamenController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private EntityManagerInterface $em,
        private PatientRepository $patientRepository,
        private ConsultationRepository $consultationRepository
    )
    {}

    #[Route('/examen/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}/{slug}', name: 'examen')]
    public function examen(Request $request, int $a = 0, int $m = 0, int $s = 0, string $slug = ""): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

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
        
        #je récupère tous les examens
        $examens = $this->consultationRepository->findAll();
        
        #j'envoie mon tableau des examens à mon rendu twig pour affichage
        return $this->render('consultation/examen.html.twig', [
            'licence' => 1,
            'examenDuJour' => 0,
            'examens' => $examens,
        ]);
    }
}

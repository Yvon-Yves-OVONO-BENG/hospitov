<?php

namespace App\Controller\Consultation;

use App\Repository\ConsultationRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('consultation')]

class ExamenDuJourController extends AbstractController
{
    public function __construct(
        private ConsultationRepository $consultationRepository
    )
    {}

    #[Route('/examen-du-jour/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'examen_du_jour')]
    public function examenDuJour(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
        
        #je récupère les examens de l'infirmière/aide-soignante
        $examens = $this->consultationRepository->findBy([
            'supprime' => 0,
            'dateParametrePrisAt' => new DateTime('now'),
        ]);
        
        #j'envoie mon tableau des examens à mon rendu twig pour affichage
        return $this->render('consultation/examen.html.twig', [
            'licence' => 1,
            'examenDuJour' => 1,
            'examens' => $examens,
        ]);
    }
}

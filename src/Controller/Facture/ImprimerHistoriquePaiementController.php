<?php

namespace App\Controller\Facture;

use App\Repository\FactureRepository;
use App\Repository\HistoriquePaiementRepository;
use App\Repository\PatientRepository;
use App\Service\ImpressionFactureService;
use App\Service\ImpressionHistoriquePaiementService;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FactureAvanceService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/facture')]
class ImprimerHistoriquePaiementController extends AbstractController
{
    public function __construct(
        private FactureRepository $factureRepository,
        private PatientRepository $patientRepository,
        private FactureAvanceService $factureAvanceService,
        private ImpressionFactureService $impressionFactureService,
        private HistoriquePaiementRepository $historiquePaiementRepository, 
        private ImpressionHistoriquePaiementService $impressionHistoriquePaiementService, 
        )
    {}
    
    #[Route('/imprimer-historique-paiement/{slug}', name: 'imprimer_historique_paiement')]
    public function ImprimerHistoriquePaiement($slug): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        $facture = $this->factureRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        $historiquePaiement = $this->historiquePaiementRepository->findOneBy([
            'facture' => $facture
        ]);
            
        $famille = $this->factureAvanceService->getHistoriqueFamille($facture);
        
        // dump($facture);
        // dd($historiqueFacture);
        $pdf = $this->impressionHistoriquePaiementService->impressionHistoriquePaiement($facture, $famille);
    
        return new Response($pdf->Output(utf8_decode("Historique de paiement de la facture ".$facture->getReference()), "I"), 200, ['content-type' => 'application/pdf']);

    }
}

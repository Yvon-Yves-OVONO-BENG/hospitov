<?php

namespace App\Controller\Produit;

use App\Repository\ProduitRepository;
use App\Service\ImpressionProduitsService;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
class ImpressionProduitsController extends AbstractController
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private ImpressionProduitsService $impressionProduitsService
    )
    {}

    #[Route('/impression-produits', name: 'impression_produits')]
    public function impressionProduits(): Response
    {
        #je récupère les produits
        $produits = $this->produitRepository->findBy([
            'kit' => 0,
            'billetDeSession' => 0,
            'echographie' => 0,
            'radio' => 0,
            'examen' => 0,
        ]);
        
        $pdf = $this->impressionProduitsService->impressionProduits($produits);
        return new Response($pdf->Output(utf8_decode("Tous les produits"), "I"), 200, ['content-type' => 'application/pdf']);
    }
}

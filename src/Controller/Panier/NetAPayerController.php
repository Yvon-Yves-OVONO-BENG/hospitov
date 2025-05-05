<?php

namespace App\Controller\Panier;

use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
class NetAPayerController extends AbstractController
{
    #[Route('/net-a-payer', name: 'net_a_payer')]
    public function netApayer(Request $request, PanierService $panierService):JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', 1);
        
        $netApayer = $panierService->getTotal($request);

        $nombreProduits = count($panierService->getDetailsPanierProduits($request));

        $maSession->set('nombreProduits', $nombreProduits);
        $maSession->set('netApayer', $netApayer);

        return new JsonResponse(['netApayer' => $netApayer ]);
    }
}

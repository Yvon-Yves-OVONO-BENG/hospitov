<?php

namespace App\Controller\Produit;

use DateTime;
use App\Repository\FactureRepository;
use App\Repository\LigneDeFactureRepository;
use App\Repository\ProduitRepository;
use App\Service\ImpressionFicheEntreeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
class ImpressionFicheEntreeController extends AbstractController
{
    public function __construct(
        private FactureRepository $factureRepository,
        private ProduitRepository $produitRepository,
        private LigneDeFactureRepository $ligneDeFactureRepository,
        private ImpressionFicheEntreeService $impressionFicheEntreeService
    )
    {}

    #[Route('/impression-fiche-entree/{periode}', name: 'impression_fiche_entree')]
    public function impressionFicheEntree(Request $request, int $periode = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        $dateDebut = null;
        $dateFin = null;

        if ($periode) 
        {
            if ($request->request->has('impressionFicheEntree')) 
            {
                // $dateDebut = DateTime::createFromFormat('Y-m-d',$request->request->get('dateDebut'));
                $dateDebut = date_create($request->request->get('dateDebut'));
                $dateFin = date_create($request->request->get('dateFin'));
                
                #je récupères les lignes de factures de la base de données
                $ligneDeFactures = $this->ligneDeFactureRepository->facturesVenduesPeriode($dateDebut, $dateFin);

                $produits = [];
                foreach ($ligneDeFactures as $ligneDeFacture) 
                {
                    if ($ligneDeFacture->getProduit()->isKit() == 0) 
                    {
                        $produits[] = $ligneDeFacture->getProduit();
                    }
                    
                }
                
                $pdf = $this->impressionFicheEntreeService->impressionFicheEntree($produits, $dateDebut, $dateFin, $periode);
            }
        } 
        else 
        {
            $periode = 0;
            #je récupères les produits de la base de données
            $produits = $this->produitRepository->findBy([
                'kit' => 0,
                'supprime' => 0,
            ], ['libelle' => 'ASC']);
            
            $pdf = $this->impressionFicheEntreeService->impressionFicheEntree($produits);
        }
        
        if ($dateDebut != null && $dateFin != null) 
        {
            return new Response($pdf->Output(utf8_decode("Fiche entrée des médicaments du ".date_format($dateDebut, 'd-m-Y')." au ".date_format($dateFin, 'd-m-Y')), "I"), 200, ['content-type' => 'application/pdf']);
        } 
        else 
        {
            return new Response($pdf->Output(utf8_decode("Fiche entrée des médicaments du ".date_format(new DateTime(("now")), 'd-m-Y')), "I"), 200, ['content-type' => 'application/pdf']);
        }
        
    
    }
}

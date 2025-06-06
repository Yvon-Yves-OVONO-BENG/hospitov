<?php

namespace App\Controller\Recette;

use DateTime;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HistoriquePaiementRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/recette')]
class RecetteCaissiereDuJourSoldeeController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private FactureRepository $factureRepository,
        private HistoriquePaiementRepository $historiquePaiementRepository,
    )
    {}

    #[Route('/recette-caissiere-du-jour-soldee', name: 'recette_caissiere_du_jour_soldee')]
    public function recetteCaissiereDuJour(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        #date du jour
        $aujourdhui = date_create(date_format(new DateTime('now'), 'Y-m-d'), timezone_open('Pacific/Nauru'));
        // $aujourdhui = new DateTime("now");
        // $date = date_create_from_format('Y-m-d', $aujourdhui);
        // dd($aujourdhui);
        #je récupère les recettes des caissières du jour

        // $avancesDuJour = $this->historiquePaiementRepository->avancesDuJour($aujourdhui);
        $avancesDuJour = $this->factureRepository->recetteAvanceDuJourCaissiere($aujourdhui);
        
        $recettesDuJourSolde = $this->factureRepository->recetteCaissiereSolde($aujourdhui);
        
        $recettesDuJourNonSolde = $this->factureRepository->recetteCaissiereNonSolde($aujourdhui);
        
        $netApayer = 0;
        $recetteAvanceDuJour = 0;
        $recetteDuJourSolde = 0;
        $recetteDuJourNonSolde = 0;
        // dd($avancesDuJour);
        foreach ($avancesDuJour as $avanceDuJour) 
        {
            // $recetteAvanceDuJour += $avanceDuJour->getMontantAvance();
            $recetteAvanceDuJour += $avanceDuJour['avance'];
            $netApayer += $avanceDuJour['netAPayer'];
        }

        foreach ($recettesDuJourSolde as $recette) 
        {
            $recetteDuJourSolde += $recette['SOMME'];
        }

        foreach ($recettesDuJourNonSolde as $recette) 
        {
            $recetteDuJourNonSolde += $recette['SOMME'];
        }
        
        $nombreRecetteDuJour = $this->factureRepository->findBy([
            'dateFactureAt' => $aujourdhui
        ]);
        
        return $this->render('recette_caissiere/recetteDuJourSoldee.html.twig', [
            'licence' => 1,
            'recetteAvanceDuJour' => $recetteAvanceDuJour,
            'avancesDuJour' => $avancesDuJour,
            'recetteDuJourSolde' => $recetteDuJourSolde,
            'recetteDuJourNonSolde' => $recetteDuJourNonSolde,
            'recettesDuJourSolde' => $recettesDuJourSolde,
            'recettesDuJourNonSolde' => $recettesDuJourNonSolde,
            'nombreRecetteDuJour' => $nombreRecetteDuJour,
            'dossier' => $this->translator->trans("Recettes"),
            'route' => $this->translator->trans("Recettes du jour")
        ]);
    }
}

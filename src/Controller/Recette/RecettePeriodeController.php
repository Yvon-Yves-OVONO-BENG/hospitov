<?php

namespace App\Controller\Recette;

use DateTime;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/recette')]
class RecettePeriodeController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        protected FactureRepository $factureRepository
    )
    {}

    #[Route('/recette-periode', name: 'recette_periode')]
    public function recettePeriode(Request $request)
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }
        
        if ($request->request->has('impressionFicheVente')) 
        {
            $dateDebut = date_create($request->request->get('dateDebut'));
            $dateFin = date_create($request->request->get('dateFin'));
            
            #je récupère les recettes des caissières du jour
            $recettesPeriode = $this->factureRepository->recettePeriode($dateDebut, $dateFin);

            $nombreRecetteDuJour = $this->factureRepository->nombreRecettePeriode($dateDebut, $dateFin);

            return $this->render('recette_caissiere/recettesPeriode.html.twig', [
                'licence' => 1,
                'dateFin' => $dateFin,
                'dateDebut' => $dateDebut,
                'recettes' => $recettesPeriode,
                'nombreRecettes' => $nombreRecetteDuJour,
                'dossier' => $this->translator->trans("Recettes"),
                'route' => $this->translator->trans("Recettes d'une période")
            ]);
        }
        
        
    }
}

<?php

namespace App\Controller\Caisses;

use App\Repository\FactureRepository;
use App\Repository\LigneDeFactureRepository;
use App\Repository\ModePaiementRepository;
use App\Repository\TypeProduitRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/caisses')]
class CaissesSepareesDuJourController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private FactureRepository $factureRepository,
        private TypeProduitRepository $typeProduitRepository,
        private ModePaiementRepository $modePaiementRepository,
        private LigneDeFactureRepository $ligneDeFactureRepository,
    )
    {}

    #[Route('/caisses-separees-du-jour', name: 'caisses_separees_du_jour')]
    public function caissesSepareesDuJour(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #la date du jour
        $aujourdhui = new DateTime('today');
        
        $recettesDuJour = $this->factureRepository->recetteDujour($aujourdhui);
        // dd($recettesDuJour);
        $recettesKitDuJour = $this->factureRepository->kitsVenduParCaissiereDuJour();

        #je récupère les factures du jour
        $facturesDuJour = $this->factureRepository->findBy([
            'dateFactureAt' => $aujourdhui,
            'annulee' => 0,
        ], ['dateFactureAt' => 'DESC']);


        return $this->render('caisses_separees/caissesSepareesDuJour.html.twig', [
            'licence' => 1,
            'aujourdhui' => $aujourdhui,
            'facturesDuJour' => $facturesDuJour,
            'recettesDuJour' => $recettesDuJour,
            'recettesKitDuJour' => $recettesKitDuJour,
            'dossier' => $this->translator->trans('Caisses séparées'),
            'route' => $this->translator->trans('Du jour'),
        ]);
    }
}

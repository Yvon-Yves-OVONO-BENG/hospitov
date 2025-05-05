<?php

namespace App\Controller\Session;

use App\Repository\BilletDeSessionRepository;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
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
#[Route('session')]

class AfficherSessionController extends AbstractController
{
    public function __construct(
        protected PanierService $panierService,
        protected TranslatorInterface $translator,
        protected BilletDeSessionRepository $billetDeSessionRepository
    )
    {}

    #[Route('/afficher-session/{session}/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_session')]
    public function afficherSession(Request $request, string $session, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        $nombreProduits = count($this->panierService->getDetailsPanierProduits($request));
        $netAPayer = $this->panierService->getTotal();

        $maSession->set('nombreProduits', $nombreProduits);
        $maSession->set('netAPayer', number_format($netAPayer, 0, '', ' '));

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
        
        switch ($session) 
        {
            case 'enAttente':
                #Les session du jour en attente
                $sessionsDuJourEnAttente = $this->billetDeSessionRepository->getBilletsDeSessionDuJourEnAttente($this->getUser());

                #j'envoie mon tableau des billetDeSessions à mon rendu twig pour affichage
                return $this->render('session/afficherSession.html.twig', [
                    'licence' => 1,
                    'enAttente' => 1,
                    'sessions' => $sessionsDuJourEnAttente,
                    'dossier' => $this->translator->trans("Session"),
                    'route' => $this->translator->trans("Les sessions en attente"),
                ]);
                break;

            case 'recues':
                #Les session du jour en reçues
                $sessionsDuJourEnRecues = $this->billetDeSessionRepository->getBilletsDeSessionDuJourRecue($this->getUser());

                #j'envoie mon tableau des billetDeSessions à mon rendu twig pour affichage
                return $this->render('session/afficherSession.html.twig', [
                    'licence' => 1,
                    'recues' => 1,
                    'sessions' => $sessionsDuJourEnRecues,
                    'dossier' => $this->translator->trans("Session"),
                    'route' => $this->translator->trans("Les sessions reçues"),
                ]);
                break;

            case 'toutesLesSessionsDuJour':
                #Les session du jour
                $sessionsDuJour = $this->billetDeSessionRepository->getBilletsDeSessionDuJour($this->getUser());

                #j'envoie mon tableau des billetDeSessions à mon rendu twig pour affichage
                return $this->render('session/afficherSession.html.twig', [
                    'licence' => 1,
                    'toutes' => 1,
                    'sessions' => $sessionsDuJour,
                    'dossier' => $this->translator->trans("Session"),
                    'route' => $this->translator->trans("Toutes les sessions du jour"),
                ]);
                break;

            case 'toutesLesSessions':
                #Les session du jour
                $toutesLesSessions = $this->billetDeSessionRepository->getBilletsDeSession($this->getUser());

                #j'envoie mon tableau des billetDeSessions à mon rendu twig pour affichage
                return $this->render('session/afficherSession.html.twig', [
                    'licence' => 1,
                    'toutes' => 1,
                    'sessions' => $toutesLesSessions,
                    'dossier' => $this->translator->trans("Session"),
                    'route' => $this->translator->trans("Toutes les sessions"),
                ]);
                break;
            
           
        }
        
        return $this->redirectToRoute('accueil');
    }
}

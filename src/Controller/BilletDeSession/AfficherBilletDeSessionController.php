<?php

namespace App\Controller\BilletDeSession;

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
#[Route('billetDeSession')]

class AfficherBilletDeSessionController extends AbstractController
{
    public function __construct(
        private PanierService $panierService,
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository
    )
    {}

    #[Route('/afficher-billet-de-session/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_billet_de_session')]
    public function afficherBilletDeSession(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
        
        #je récupère toutes les billetDeSessions
        $billetDeSessions = $this->produitRepository->findBy([
            'billetDeSession' => 1
        ]);
        
        #j'envoie mon tableau des billetDeSessions à mon rendu twig pour affichage
        return $this->render('billetDeSession/afficherBilletDeSession.html.twig', [
            'licence' => 1,
            'billetDeSessions' => $billetDeSessions,
            'dossier' => $this->translator->trans("Billet de session"),
            'route' => $this->translator->trans("Liste billets de session"),
        ]);
    }
}

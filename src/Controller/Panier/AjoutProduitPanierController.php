<?php

namespace App\Controller\Panier;

use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
class AjoutProduitPanierController extends AbstractController
{
    public function __construct(
        private RequestStack $request,
        private PanierService $panierService, 
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository, 
        )
    {}

    #[Route('/ajout-produit-panier', name: 'ajout_produit_panier', methods:"POST")]
    public function ajoutProduitPanier(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);

        $produitId = (int)$request->request->get('produit_id');
        $quantite = (int)$request->request->get('quantite');
        
        // 0. Sécurisation : est-ce que le produit existe ?
        $produit = $this->produitRepository->find($produitId); 
        
        $slug = $produit->getSlug();
        
        $this->panierService->ajout($slug, $quantite);
        
        if ($produit) 
        {
            $this->addFlash('info', $this->translator->trans('Produit ajouté dans la facture avec succès !'));
        } 
        else 
        {
            $this->addFlash('info', $this->translator->trans('Kit ajouté dans la facture avec succès !'));
        }
    
        return new JsonResponse(['success' => true ]);
       
    }

}
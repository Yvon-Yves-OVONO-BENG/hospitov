<?php

namespace App\Controller\Panier;

use App\Entity\Produit;
use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SupprimerProduitDuPanierController extends AbstractController
{
    public function __construct(
        private PanierService $panierService,
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository, 
        )
    {}

    #[Route('/supprimer-produit-du-panier/{slug}', name: 'supprimer_produit_du_panier')]
    public function supprimer(Request $request, string $slug, Produit $produit): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        $maSession->set('produitsManquants', null);

        if (!$this->getUser()) 
        {
            return $this->redirectToRoute('app_logout');
        }
        
        // if (!$this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) 
        // {
        //     return new Response('Token CSRF invalide', 400);
        // }

        $produit = $this->produitRepository->findOneBySlug([
            'slug' => $slug
        ]);

        if (!$produit) 
        {
           throw $this->createNotFoundException("Le produit $slug n'existe pas et ne peut pes être supprimé !");
        }

        $this->panierService->supprimer($slug);

        $this->addFlash('info', $this->translator->trans('Produit retiré du panier avec succès !'));
        
        $maSession->set('suppression', 1);

        if (count($this->panierService->getDetailsPanierProduits($request)) != 0) 
        {
            return $this->redirectToRoute("panier_afficher", ['s' => 1 ]);
        } 
        else 
        {
            return $this->redirectToRoute("accueil", ['s' => 1 ]);
        }
        
    }
}

<?php

namespace App\Controller\Produit;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class DetailsProduitController extends AbstractController
{
    public function __construct(
        private ProduitRepository $produitRepository)
    {}

    #[Route('/details-produit/{slug}', name: 'details_produit')]
    public function detailsProduit(Request $request, int $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        $produit = $this->produitRepository->findOneBySlug([
            'slug' => $slug
        ]);

        return $this->render('produit/detailsProduit.html.twig', [
            'licence' => 1,
            'produit' => $produit,
        ]);
    }
}

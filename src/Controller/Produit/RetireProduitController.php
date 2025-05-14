<?php

namespace App\Controller\Produit;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/produit')]
class RetireProduitController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository
    )
    {}

    #[Route('/retirer-produit', name: 'retirer_produit')]
    public function retirerProduit(Request $request)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        $id = $request->request->get('id');
        
        $produit = $this->produitRepository->find($id);
        
        if ($produit->isRetire() == 0) 
        {
            $produit->setRetire(1);
        } else {
            $produit->setRetire(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($produit);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'retirer' => $produit->isRetire() ]);
    }
}

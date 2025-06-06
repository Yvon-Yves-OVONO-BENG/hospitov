<?php

namespace App\Controller\Produit;

use DateTime;
use App\Entity\ConstantsClass;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('produit')]

class AfficherProduitSeuilController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository
    )
    {}

    #[Route('/afficher-produits-seuil/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_produits_seuil')]
    public function afficherProduitsSeuil(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

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
        
        #date du jour
        $aujourdhui = new DateTime('now');

        #je récupère toutes les produits
        $produits = [];

        $produis = $this->produitRepository->findBy([
            'kit' => 0,
            'examen' => 0, 
            'billetDeSession' => 0,
            'echographie' => 0,
            'radio' => 0,
            'examen' => 0,
            'supprime' => 0
        ], ['libelle' => 'ASC' ]);

        foreach ($produis as $produit) 
        {
            if (($produit->getLot()->getQuantite() - $produit->getLot()->getVendu()) <= $produit->getQuantiteSeuil()) 
            {
                $produits[] = $produit;
            }
        }
        
        #j'envoie mon tableau des produits à mon rendu twig pour affichage
        return $this->render('produit/afficherProduit.html.twig', [
            'licence' => 1,
            'seuil' => 1,
            'aujourdhui' => $aujourdhui,
            'produits' => $produits,
            'dossier' => $this->translator->trans("Produit"),
            'route' => $this->translator->trans("Les produits seuils"),
        ]);
    }
}

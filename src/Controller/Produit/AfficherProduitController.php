<?php

namespace App\Controller\Produit;

use DateTime;
use App\Entity\ConstantsClass;
use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('produit')]
class AfficherProduitController extends AbstractController
{
    public function __construct(
        private PanierService $panierService,
        private TranslatorInterface $translator,
        private ProduitRepository $produitRepository,
    )
    {}

    #[Route('/afficher-produits/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}/{p<[0-1]{1}>}/{caisse}', name: 'afficher_produits')]
    public function afficherProduits(Request $request, int $a = 0, int $m = 0, int $s = 0, int $p = 0,
    ?string $caisse = null): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        $nombreProduits = count($this->panierService->getDetailsPanierProduits($request));
        $totalApayer = $this->panierService->getTotal($request);

        $maSession->set('nombreProduits', $nombreProduits);
        $maSession->set('totalApayer', $totalApayer);

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

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la mise à jour
        if ($p == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', 1);
            $maSession->set('misAjour', null);
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
        
        #je récupère tous les produits
        if ($this->getUser() && (in_array(ConstantsClass::ROLE_CAISSIERE_ACCUEIL, $this->getUser()->getRoles()) ||
        in_array(ConstantsClass::ROLE_CAISSIERE_PHARMACIE, $this->getUser()->getRoles()))) 
        {
            $produits = $this->produitRepository->findBy([
                'kit' => 0,
                'examen' => 0,
                'radio' => 0,
                'echographie' => 0,
                'supprime' => 0,
                'retire' => 0,
            ], ['libelle' => 'ASC' ]);

            $billets = [];
            $carnets = [];
            $echographies = [];
            $examens = [];
            $kits = [];
            $radios = [];
            
            switch ($caisse) 
            {
                case 'billet':
                    #je récupère les billest de session
                    $billets = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'billetDeSession' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $billets,
                        'billet' => 1,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les billets de session"),
                    ]);
                    break;

                case 'carnet':
                    #je récupère les carnet
                    $carnets = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'carnet' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'carnet' => 1,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $carnets,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les carnets"),
                    ]);

                    break;

                case 'echographie':
                    #je récupère les echographies
                    $echographies = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'echographie' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'echographie' => 1,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $echographies,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les examens"),
                    ]);
                    break;

                case 'examen':
                    #je récupère les examens
                    $examens = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'examen' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'examen' => 1,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $examens,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les examens"),
                    ]);
                    break;


                case 'kit':
                    #je récupère les kits
                    $kits = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'kit' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'kit' => 1,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $kits,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les kits"),
                    ]);
                    break;
                
                case 'radio':
                    #je récupère les radios
                    $radios = $this->produitRepository->findBy([
                        'supprime' => 0,
                        'radio' => 1
                    ]);

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/afficherProduitsCaisse.html.twig', [
                        'licence' => 1,
                        'seuil' => 0,
                        'radio' => 1,
                        'aujourdhui' => $aujourdhui,
                        'produits' => $radios,
                        'dossier' => $this->translator->trans("Produit"),
                        'route' => $this->translator->trans("Les radios"),
                    ]);
                    break;
                
            }


        } 
        else 
        {
            $produits = $this->produitRepository->findBy([
                'kit' => 0,
                'examen' => 0, 
                'billetDeSession' => 0,
                'echographie' => 0,
                'radio' => 0,
                'examen' => 0
            ], ['libelle' => 'ASC' ]);
            
        }
        
        
        #j'envoie mon tableau des produits à mon rendu twig pour affichage
        return $this->render('produit/afficherProduit.html.twig', [
            'licence' => 1,
            'seuil' => 0,
            'aujourdhui' => $aujourdhui,
            'produits' => $produits,
            'dossier' => $this->translator->trans("Produit"),
            'route' => $this->translator->trans("Les produits"),
        ]);
    }
}

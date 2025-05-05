<?php

namespace App\Controller\Produit;

use App\Entity\User;
use App\Repository\LigneDeFactureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_USER')]
#[Route('/produit')]
class ProduitsCaissePayesController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private UserRepository $userRepository,
        private LigneDeFactureRepository $ligneDeFactureRepository
    )
    {}

    #[Route('/produits-caisse-payes/{produitPaye}', name: 'produits_caisse_payes')]
    public function produitsCaissePayes(Request $request, string $produitPaye): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('misAjour', null);
        
        /**
         * @var User
         */
        $user = $this->getUser();
        switch ($produitPaye) 
            {
                case 'billet':
                    #je récupère les billest de session
                    $billets = $this->ligneDeFactureRepository->getBilletsDeSessionDuJour($this->userRepository->find($user->getId()));
                    
                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'produits' => $billets,
                        'billet' => 1,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les billets de session"),
                    ]);
                    break;

                case 'carnet':
                    #je récupère les carnet
                    $carnets = $this->ligneDeFactureRepository->getCarnetDuJour($this->userRepository->find($user->getId()));
        
                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'carnet' => 1,
                        'produits' => $carnets,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les carnets"),
                    ]);

                    break;

                case 'echographie':
                    #je récupère les echographies
                    $echographies = $this->ligneDeFactureRepository->getEchographiesDuJour($this->userRepository->find($user->getId()));
        
                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'echographie' => 1,
                        'produits' => $echographies,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les examens"),
                    ]);
                    break;

                case 'examen':
                    #je récupère les examens
                    $examens = $this->ligneDeFactureRepository->getExamenDuJour($this->userRepository->find($user->getId()));

                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'examen' => 1,
                        'produits' => $examens,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les examens"),
                    ]);
                    break;


                case 'kit':
                    #je récupère les kits
                    $kits = $this->ligneDeFactureRepository->getKitDuJour($this->userRepository->find($user->getId()));
        
                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'kit' => 1,
                        'produits' => $kits,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les kits"),
                    ]);
                    break;
                
                case 'radio':
                    #je récupère les radios
                    $radios= $this->ligneDeFactureRepository->getRadioDuJour($this->userRepository->find($user->getId()));
        
                    #j'envoie mon tableau des produits à mon rendu twig pour affichage
                    return $this->render('produit/produits_caisse_payes.html.twig', [
                        'licence' => 1,
                        'radio' => 1,
                        'produits' => $radios,
                        'dossier' => $this->translator->trans("Produits payés"),
                        'route' => $this->translator->trans("Les radios"),
                    ]);
                    break;
                
            }

            return $this->redirectToRoute('accueil');
    }
}

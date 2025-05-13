<?php

namespace App\Service;

use App\Entity\ConstantsClass;
use App\Entity\Skill;
use App\Entity\Lesson;
use App\Entity\Sequence;
use App\Entity\Evaluation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
// use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class AjoutQuantiteProduitService
{
    public function __construct(
        private Security $security, 
        private RequestStack $request,
        private EntityManagerInterface $em, 
        private PanierService $panierService,
        private TranslatorInterface $translator, 
        )
    {}

    
    public function ajoutQuantite(Request $request)
    {
        $maSession = $this->request->getSession();
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);

        $panier = $this->request->getSession()->get('panier', []);

        $nombreProduits = $request->request->get('nombreProduits');
        
        // Si les quantités ont été saisies
        for ($i=1; $i <= $nombreProduits ; $i++) 
        {
            $detailsPanier = $this->panierService->getDetailsPanierProduits($request);

            if ($request->request->get('qte'.$i) == 0) 
            {
                //je supprime le produit $slug de mon panier
                unset($panier[$detailsPanier[$i-1]->produit->getSlug()]);

                ///je met mon panier à jour
                $maSession->set('panier', $panier);
                // $this->request->getSession()->set('panier', $panier);
            }
            else
            {
                $detailsPanier[$i-1]->qte = $request->request->get('qte'.$i);
                $slug = $detailsPanier[$i-1]->produit->getSlug();
                $panier[$slug] = $request->request->get('qte'.$i);
            }

            
        }
        
        // 6. Enregistrer le tableau mis à jour dans la session
        $maSession->set('panier', $panier);
    }

}

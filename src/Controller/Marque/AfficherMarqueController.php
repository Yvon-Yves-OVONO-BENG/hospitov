<?php

namespace App\Controller\Marque;

use App\Entity\ConstantsClass;
use App\Repository\MarqueRepository;
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
#[Route('marque')]

class AfficherMarqueController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private MarqueRepository $marqueRepository,
    )
    {}

    #[Route('/afficher-marque/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_marque')]
    public function afficherMarque(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

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
        
        if ($this->getUser() && in_array(ConstantsClass::ROLE_ADMINISTRATEUR, $this->getUser()->getRoles())) 
        {
            #je récupère toutes les marques
            $marques = $this->marqueRepository->findAll();
        } 
        elseif ($this->getUser() && in_array(ConstantsClass::ROLE_GESTIONNAIRE, $this->getUser()->getRoles())) 
        {
            #je récupère toutes les marques
            $marques = $this->marqueRepository->findBy([
                'supprime' => 0
            ]);
        }
        
        #j'envoie mon tableau des marques à mon rendu twig pour affichage
        return $this->render('marque/afficherMarque.html.twig', [
            'licence' => 1,
            'marques' => $marques,
            'dossier' => $this->translator->trans("Marque"),
            'route' => $this->translator->trans("Les marques"),
        ]);
    }
}

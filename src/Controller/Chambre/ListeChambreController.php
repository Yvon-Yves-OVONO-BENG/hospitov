<?php

namespace App\Controller\Chambre;

use App\Entity\ConstantsClass;
use App\Repository\ChambreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 */

 #[Route('/chambre')]
class ListeChambreController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected ChambreRepository $chambreRepository
    )
    {}

    #[Route('/liste-chambre/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'liste_chambre')]
    public function listeChambre(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
            #je récupère toutes les categories
            $chambres = $this->chambreRepository->findAll();
        } 
        elseif ($this->getUser() && in_array(ConstantsClass::ROLE_GESTIONNAIRE, $this->getUser()->getRoles()))  
        {
            #je récupère toutes les categories
            $chambres = $this->chambreRepository->findBy([
                'enService' => 1,
                'supprime' => 0,
            ]);
        }
        
        

        return $this->render('chambre/listeChambre.html.twig', [
            'licence' => 1,
            'chambres' => $chambres,
            'dossier' => $this->translator->trans("Chambre"),
            'route' => $this->translator->trans("Les chambres"),
        ]);
    }
}

<?php

namespace App\Controller\AttributionAmbulance;

use App\Entity\ConstantsClass;
use App\Repository\AttributionAmbulanceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 */

 #[Route('/attributionAmbulance')]
class ListeAttributionAmbulanceController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private AttributionAmbulanceRepository $attributionAmbulanceRepository
    )
    {}

    #[Route('/liste-attribution-ambulance/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'liste_attribution_ambulance')]
    public function listeAttributionAmbulance(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
            $attributionAmbulances = $this->attributionAmbulanceRepository->findAll();
        } 
        elseif($this->getUser() && in_array(ConstantsClass::ROLE_GESTIONNAIRE, $this->getUser()->getRoles())) 
        {
            #je récupère toutes les categories
            $attributionAmbulances = $this->attributionAmbulanceRepository->findBy([
                'supprime' => 0
            ]);
        }
        
        return $this->render('attributionAmbulance/listeAttributionAmbulance.html.twig', [
            'licence' => 1,
            'attributionAmbulances' => $attributionAmbulances,
            'dossier' => $this->translator->trans("Attribution ambulance"),
            'route' => $this->translator->trans("Les attributions ambulance"),
        ]);
    }
}

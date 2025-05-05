<?php

namespace App\Controller\TypeChambre;

use App\Entity\ConstantsClass;
use App\Repository\TypeChambreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 */

 #[Route('/typeChambre')]
class ListeTypeChambreController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected TypeChambreRepository $typeChambreRepository
    )
    {}

    #[Route('/liste-type-chambre/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'liste_type_chambre')]
    public function listeTypeChambre(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
            $typeChambres = $this->typeChambreRepository->findAll();
        } 
        elseif($this->getUser() && in_array(ConstantsClass::ROLE_GESTIONNAIRE, $this->getUser()->getRoles()) )
        {
            #je récupère toutes les categories
            $typeChambres = $this->typeChambreRepository->findBy([
                'supprime' => 0,
            ]);
        }
        
        return $this->render('typeChambre/listeTypeChambre.html.twig', [
            'licence' => 1,
            'typeChambres' => $typeChambres,
            'dossier' => $this->translator->trans("Type chambre"),
            'route' => $this->translator->trans("Liste types chambres"),
        ]);
    }
}

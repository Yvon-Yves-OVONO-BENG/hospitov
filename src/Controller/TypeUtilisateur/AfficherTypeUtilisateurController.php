<?php

namespace App\Controller\TypeUtilisateur;

use App\Repository\TypeUtilisateurRepository;
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
#[Route('typeUtilisateur')]

class AfficherTypeUtilisateurController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private TypeUtilisateurRepository $typeUtilisateurRepository
    )
    {}

    #[Route('/afficher-typeUtilisateur/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_typeUtilisateur')]
    public function afficherTypeUtilisateur(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
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
        
        #je récupère toutes les typeUtilisateurs
        $typeUtilisateurs = $this->typeUtilisateurRepository->findAll();
        
        #j'envoie mon tableau des typeUtilisateurs à mon rendu twig pour affichage
        return $this->render('typeUtilisateur/afficherTypeUtilisateur.html.twig', [
            'licence' => 1,
            'typeUtilisateurs' => $typeUtilisateurs,
            'dossier' => $this->translator->trans("Type personnel"),
            'route' => $this->translator->trans("Liste personnel"),
        ]);
    }
}

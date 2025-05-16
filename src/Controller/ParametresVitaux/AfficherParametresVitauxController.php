<?php

namespace App\Controller\ParametresVitaux;

use App\Repository\ParametresVitauxRepository;
use App\Repository\UserRepository;
use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('parametres-vitaux')]

class AfficherParametresVitauxController extends AbstractController
{
    public function __construct(
        private PanierService $panierService,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private ParametresVitauxRepository $parametresVitauxRepository
    )
    {}

    #[Route('/afficher-parametres-vitaux/{parametre}/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_parametres_vitaux')]
    public function afficherParametresVitaux(Request $request, string $parametre, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
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
        
        switch ($parametre) 
        {
            case 'jour':
                #je récupère toutes les parametresVitauxs
                /**
                 * @var User
                 */
                $user = $this->getUser();

                $parametresVitauxDuJour = $this->parametresVitauxRepository->getParametresVitauxDuJour($this->userRepository->find($user->getId())->getId());
                
                #j'envoie mon tableau des parametresVitauxs à mon rendu twig pour affichage
                return $this->render('parametresVitaux/afficherParametresVitaux.html.twig', [
                    'licence' => 1,
                    'parametresVitaux' => $parametresVitauxDuJour,
                    'dossier' => $this->translator->trans("Paramètres vitaux"),
                    'route' => $this->translator->trans("Liste des paramètres vitaux du jour"),
                ]);
                break;
            
            case 'tousLesParametres':
                #je récupère toutes les parametresVitauxs
                $parametresVitaux = $this->parametresVitauxRepository->getTousLesParametresVitaux($this->getUser());
                
                #j'envoie mon tableau des parametresVitauxs à mon rendu twig pour affichage
                return $this->render('parametresVitaux/afficherParametresVitaux.html.twig', [
                    'licence' => 1,
                    'parametresVitaux' => $parametresVitaux,
                    'dossier' => $this->translator->trans("Paramètres vitaux"),
                    'route' => $this->translator->trans("Liste des paramètres vitaux"),
                ]);
                break;
        }
        
        #j'envoie mon tableau des parametresVitauxs à mon rendu twig pour affichage
        return $this->redirectToRoute('accueil');
    }
}

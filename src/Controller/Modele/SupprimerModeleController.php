<?php

namespace App\Controller\Modele;

use App\Repository\ModeleRepository;
use Doctrine\ORM\EntityManagerInterface;
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
#[Route('/modele')]
class SupprimerModeleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ModeleRepository $modeleRepository,
    )
    {}
    
    #[Route('/supprimer-modele/{slug}', name: 'supprimer_modele')]
    public function supprimerModele(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        # je récupère l'modele dont je veux modifier l'état
        $modele = $this->modeleRepository->findOneBySlug([
            'slug' => $slug
        ]);

        if ($modele->isSupprime() == 0 ) 
        {
            #je prépare ma requete à la suppression
            $modele->setSupprime(1)->setVisible(0);

            #j'exécute ma requete
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Modèle supprimé avec succès !'));
                
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('suppression', 1);
            
            #je redirige vers la liste des modeles
            return $this->redirectToRoute('liste_modele', ['s' => 1 ]);
        } 
        else 
        {
            #je prépare ma requete à la suppression
            $modele->setSupprime(0)->setVisible(1);

            #j'exécute ma requete
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Modèle rétabli avec succès !'));
                
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je redirige vers la liste des modeles
            return $this->redirectToRoute('liste_modele', ['m' => 1 ]);
        }
        
    }
}

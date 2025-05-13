<?php

namespace App\Controller\Batiment;

use App\Repository\BatimentRepository;
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
#[Route('/batiment')]
class SupprimerBatimentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private BatimentRepository $batimentRepository,
    )
    {}
    
    #[Route('/supprimer-batiment/{slug}', name: 'supprimer_batiment')]
    public function supprimerBatiment(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        # je récupère l'batiment dont je veux modifier l'état
        $batiment = $this->batimentRepository->findOneBySlug([
            'slug' => $slug
        ]);

        if ($batiment->isSupprime() == 1) 
        {
            #je prépare ma requete à la restauration
            $batiment->setSupprime(0);

            #j'exécute ma requete
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Bâtiment rétabli avec succès !'));
                
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);

            #je redirige vers la liste des batiments
            return $this->redirectToRoute('liste_batiment', ['m' => 1 ]);

        } 
        else 
        {
            #je prépare ma requete à la suppression
            $batiment->setSupprime(1);

            #j'exécute ma requete
            $this->em->flush();

            #j'affiche le message de confirmation
            $this->addFlash('info', $this->translator->trans('Bâtiment supprimé avec succès !'));
                
            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('suppression', 1);

            #je redirige vers la liste des batiments
            return $this->redirectToRoute('liste_batiment', ['s' => 1 ]);
        }
        
    }
}

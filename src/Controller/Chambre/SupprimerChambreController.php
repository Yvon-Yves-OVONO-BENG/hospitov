<?php

namespace App\Controller\Chambre;

use App\Repository\ChambreRepository;
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
#[Route('/chambre')]
class SupprimerChambreController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected ChambreRepository $chambreRepository,
    )
    {}
    
    #[Route('/supprimer-chambre/{slug}', name: 'supprimer_chambre')]
    public function supprimerChambre(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        # je récupère l'chambre dont je veux modifier l'état
        $chambre = $this->chambreRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je prépare ma requete à la suppression
        $chambre->setSupprime(1);

        #j'exécute ma requete
        $this->em->flush();

        #j'affiche le message de confirmation
        $this->addFlash('info', $this->translator->trans('Chambre supprimée avec succès !'));
            
        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je redirige vers la liste des chambres
        return $this->redirectToRoute('liste_chambre', ['s' => 1 ]);
    }
}

<?php

namespace App\Controller\AttributionAmbulance;

use App\Repository\AttributionAmbulanceRepository;
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
#[Route('/attributionAmbulance')]
class SupprimerAttributionAmbulanceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private AttributionAmbulanceRepository $attributionAmbulanceRepository,
    )
    {}
    
    #[Route('/supprimer-attribution-ambulance/{slug}', name: 'supprimer_attribution_ambulance')]
    public function supprimerAttributionAmbulance(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        # je récupère l'attributionAmbulance dont je veux modifier l'état
        $attributionAmbulance = $this->attributionAmbulanceRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je prépare ma requete à la suppression
        $attributionAmbulance->setSupprime(1);

        #j'exécute ma requete
        $this->em->flush();

        #j'affiche le message de confirmation
        $this->addFlash('info', $this->translator->trans('Attribution Ambulance supprimée avec succès !'));
            
        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je redirige vers la liste des attributionAmbulances
        return $this->redirectToRoute('liste_attribution_ambulance', ['s' => 1 ]);
    }
}

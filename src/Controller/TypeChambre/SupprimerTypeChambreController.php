<?php

namespace App\Controller\TypeChambre;

use App\Repository\TypeChambreRepository;
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
#[Route('/typeChambre')]
class SupprimerTypeChambreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private TypeChambreRepository $typeChambreRepository,
    )
    {}
    
    #[Route('/supprimer-type-chambre/{slug}', name: 'supprimer_type_chambre')]
    public function supprimerTypeChambre(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        # je récupère l'typeChambre dont je veux modifier l'état
        $typeChambre = $this->typeChambreRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je prépare ma requete à la suppression
        $typeChambre->setSupprime(1)->setEnService(0);

        #j'exécute ma requete
        $this->em->flush();

        #j'affiche le message de confirmation
        $this->addFlash('info', $this->translator->trans('Type Chambre supprimé avec succès !'));
            
        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je redirige vers la liste des typeChambres
        return $this->redirectToRoute('liste_type_chambre', ['s' => 1 ]);
    }
}

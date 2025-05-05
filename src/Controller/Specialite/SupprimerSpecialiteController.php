<?php

namespace App\Controller\Specialite;

use App\Repository\SpecialiteRepository;
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
#[Route('/specialite')]
class SupprimerSpecialiteController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected SpecialiteRepository $specialiteRepository
    ){}

    #[Route('/supprimer-specialite/{slug}', name: 'supprimer_specialite')]
    public function supprimerSpecialite(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        #je récupère la catégorie à supprimer
        $specialite = $this->specialiteRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je prépare ma requête à la suppression
        $specialite->setSupprime(1);

        #j'exécute ma requête
        $this->em->flush();

        #j'affiche le message de confirmation d'ajout
        $this->addFlash('info', $this->translator->trans('Département / Spécialité supprimée avec succès !'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_specialite', [ 's' => 1 ]);
    }
}

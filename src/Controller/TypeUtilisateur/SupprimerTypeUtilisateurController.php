<?php

namespace App\Controller\TypeUtilisateur;

use App\Repository\TypeUtilisateurRepository;
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
#[Route('/typeUtilisateur')]
class SupprimerTypeUtilisateurController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private TypeUtilisateurRepository $typeUtilisateurRepository
    ){}

    #[Route('/supprimer-typeUtilisateur/{slug}', name: 'supprimer_typeUtilisateur')]
    public function supprimerTypeUtilisateur(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        #je récupère la catégorie à supprimer
        $typeUtilisateur = $this->typeUtilisateurRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #j'exécute ma requête
        $this->em->remove($typeUtilisateur);
        $this->em->flush();

        #j'affiche le message de confirmation d'ajout
        $this->addFlash('info', $this->translator->trans('Type personnel supprimé avec succès !'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_typeUtilisateur', [ 's' => 1 ]);
    }
}

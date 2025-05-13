<?php

namespace App\Controller\ParametresVitaux;

use App\Repository\ParametresVitauxRepository;
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
#[Route('/parametres-vitaux')]
class SupprimerParametresVitauxController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ParametresVitauxRepository $parametresVitauxRepository
    ){}

    #[Route('/supprimer-parametres-vitaux/{slug}', name: 'supprimer_parametres_vitaux')]
    public function supprimerParametresVitaux(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        #je récupère la catégorie à supprimer
        $parametresVitaux = $this->parametresVitauxRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je prépare ma requête à la suppression#
        $parametresVitaux->setSupprime(1);

        #j'exécute ma requête
        $this->em->flush();

        #j'affiche le message de confirmation d'ajout
        $this->addFlash('info', $this->translator->trans('Paramètres vitaux supprimés avec succès !'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_parametres_vitaux', [ 's' => 1 ]);
    }
}

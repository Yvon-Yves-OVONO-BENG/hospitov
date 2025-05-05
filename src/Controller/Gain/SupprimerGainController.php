<?php

namespace App\Controller\Gain;

use App\Repository\GainRepository;
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
#[Route('/gain')]
class SupprimerGainController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected GainRepository $gainRepository
    )
    {}

    #[Route('/supprimer-gain/{slug}', name: 'supprimer_gain')]
    public function supprimerGain(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        

        #je récupère la catégorie à supprimer
        $gain = $this->gainRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je prépare ma requête à la suppression
        $this->em->remove($gain);

        #j'exécute ma requête
        $this->em->flush();

        #j'affiche le message de confirmation d'ajout
        $this->addFlash('info', $this->translator->trans('Gain supprimé avec succès !'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);

        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_gain', [ 's' => 1 ]);
    }
}

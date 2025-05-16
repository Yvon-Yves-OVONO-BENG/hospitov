<?php

namespace App\Controller\PrimeSpeciale;

use App\Repository\PrimeSpecialeRepository;
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
#[Route('/primeSpeciale')]
class SupprimerPrimeSpecialeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private PrimeSpecialeRepository $primeSpecialeRepository
    )
    {}

    #[Route('/supprimer-prime-speciale/{slug}', name: 'supprimer_prime_speciale')]
    public function supprimerPrimeSpeciale(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        

        #je récupère la catégorie à supprimer
        $primeSpeciale = $this->primeSpecialeRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je prépare ma requête à la suppression
        $this->em->remove($primeSpeciale);

        #j'exécute ma requête
        $this->em->flush();

        #j'affiche le message de confirmation d'ajout
        $this->addFlash('info', $this->translator->trans('PrimeSpeciale supprimé avec succès !'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);

        #je retourne à la liste des catégories
        return $this->redirectToRoute('afficher_primeSpeciale', [ 's' => 1 ]);
    }
}

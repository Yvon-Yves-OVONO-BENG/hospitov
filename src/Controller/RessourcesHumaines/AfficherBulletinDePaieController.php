<?php

namespace App\Controller\RessourcesHumaines;

use App\Repository\BulletinSalaireRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class AfficherBulletinDePaieController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private BulletinSalaireRepository $bulletinSalaireRepository,
    )
    {}
    
    #[Route('/afficher-bulletin-de-paie/{slug}', name: 'afficher_bulletin_de_paie')]
    public function afficherBulletinDePaie(Request $request, $slug): Response
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
        $maSession->set('miseAjour', null);

        $personnel = $this->userRepository->findOneBySlug(['slug' => $slug ]);

        $bulletinDeSalaire = $this->bulletinSalaireRepository->findOneBy([
            'personnel' => $personnel,
            'mois' => (new \DateTime())->format('m'),
            'annee' => (new \DateTime())->format('Y'),
        ]);

        return $this->render('ressourcesHumaines/afficherBulletinDePaie.html.twig', [
            'personnel' => $personnel,
            'bulletinDeSalaire' => $bulletinDeSalaire,
            'dossier' => $this->translator->trans("Ressources Humaines"),
            'route' => $this->translator->trans("Bulletin de paie de : ".$personnel->getNom()),
        ]);
    }
}

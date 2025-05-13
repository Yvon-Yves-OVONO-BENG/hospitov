<?php

namespace App\Controller\Lit;

use App\Repository\LitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/lit')]
class SupprimerLitController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private LitRepository $litRepository,
    )
    {}
    
    #[Route('/supprimer-lit/{slug}', name: 'supprimer_lit')]
    public function supprimerLit(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        # je récupère l'lit dont je veux modifier l'état
        $lit = $this->litRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je prépare ma requete à la suppression
        $lit->setSupprime(1)->setEnService(0);

        #j'exécute ma requete
        $this->em->flush();

        #j'affiche le message de confirmation
        $this->addFlash('info', $this->translator->trans('Lit supprimé avec succès !'));
            
        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('suppression', 1);
        
        #je redirige vers la liste des lits
        return $this->redirectToRoute('liste_lit', ['s' => 1 ]);
    }
}

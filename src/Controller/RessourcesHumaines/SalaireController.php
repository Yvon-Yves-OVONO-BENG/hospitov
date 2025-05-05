<?php

namespace App\Controller\RessourcesHumaines;

use App\Repository\BulletinSalaireRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class SalaireController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private BulletinSalaireRepository $bulletinSalaireRepository,
    )
    {}
    
    #[Route('/salaire', name: 'salaire')]
    public function salaire(Request $request): Response
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
        $maSession->set('miseAjour', null);

        #je récupère le personnel dont le statut est actif

        $bulletinDeSalaires = $this->bulletinSalaireRepository->findBy([
            'mois' => (new \DateTime())->format('m'),
            'annee' => (new \DateTime())->format('Y'),
        ]);

        return $this->render('ressourcesHumaines/salaire.html.twig', [
            'bulletinDeSalaires' => $bulletinDeSalaires,
            'dossier' => $this->translator->trans("Ressources Humaines"),
            'route' => $this->translator->trans("Salaires"),
           
        ]);
    }
}

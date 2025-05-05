<?php

namespace App\Controller\RessourcesHumaines;

use App\Repository\UserRepository;
use App\Service\GenererBulletinDePaieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_USER')]
#[Route('ressourcesHumaines')]
class GenererBulletinDePaieController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private GenererBulletinDePaieService $genererBulletinDePaieService, 
    )
    {}

    #[Route('/generer-bulletin-de-paie/{toutLePersonnel}/{slug}', name: 'generer_bulletin_salaire_de_paie')]
    public function GenererBulletinDePaie(Request $request, int $toutLePersonnel = 0, ?string $slug = null)
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        /**
         * @var User
         */
        $user = $this->getUser();

        if ($toutLePersonnel == 0) 
        {
            $personnel = $this->userRepository->findOneBySlug(['slug' => $slug]);
            $this->genererBulletinDePaieService->genererBulletin(
                $personnel,
                date('n'),
                date('Y'),
                $user->getId()
            );
        } 
        else 
        {
            #je récupère le personnel dont le statut est actif
            $personnels = $this->userRepository->getUserApprobation();
            
            foreach ($personnels as $personnel) 
            {
                $this->genererBulletinDePaieService->genererBulletin(
                    $personnel,
                    date('n'),
                    date('Y'),
                    $user->getId()
                );
            }
        }
            
        $this->addFlash('info', $this->translator->trans('Bulletin(s) généré(s) avec succès'));

        #j'affecte 1 à ma variable pour afficher le message
        $maSession->set('ajout', 1);
        
        if ($toutLePersonnel == 1) 
        {
            return $this->redirectToRoute('approbation');
        } 
        else 
        {
            return $this->redirectToRoute('approbation');
        }
        
    }
}
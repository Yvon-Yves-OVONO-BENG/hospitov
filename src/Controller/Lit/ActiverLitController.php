<?php

namespace App\Controller\Lit;

use App\Repository\LitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/lit')]
class ActiverLitController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private LitRepository $litRepository
    )
    {}

    #[Route('/activer-lit', name: 'activer_lit', methods: 'POST')]
    public function activerLit(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        $litId = (int)$request->request->get('lit_id');
        
        $lit = $this->litRepository->find($litId);
        
        if ($lit->isEnService() == 0) 
        {
            $lit->setEnService(1);
            $lit->setSupprime(0);
        } 
        else 
        {
            $lit->setEnService(0);
            $lit->setSupprime(1);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($lit);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des lits
        return new JsonResponse(['success' => true, 'enService' => $lit->isEnService() ]);
    }
}

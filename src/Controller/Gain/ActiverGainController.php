<?php

namespace App\Controller\Gain;

use App\Repository\GainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/gain')]
class ActiverGainController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private GainRepository $gainRepository
    )
    {}

    #[Route('/activer-gain', name: 'activer_gain', methods: 'POST')]
    public function activerGain(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('miseAjour', null);
        
        $gainId = (int)$request->request->get('gain_id');
        
        $gain = $this->gainRepository->find($gainId);
        
        if ($gain->isActif() == 0) 
        {
            $gain->setActif(1);
        } else {
            $gain->setActif(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($gain);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'enService' => $gain->isActif() ]);
    }
}

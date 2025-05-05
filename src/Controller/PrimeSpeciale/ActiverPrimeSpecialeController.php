<?php

namespace App\Controller\PrimeSpeciale;

use App\Repository\PrimeSpecialeRepository;
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
#[Route('/retenue')]
class ActiverPrimeSpecialeController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected PrimeSpecialeRepository $retenueRepository
    )
    {}

    #[Route('/activer-retenue', name: 'activer_retenue', methods: 'POST')]
    public function activerPrimeSpeciale(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('miseAjour', null);
        
        $retenueId = (int)$request->request->get('retenue_id');
        
        $retenue = $this->retenueRepository->find($retenueId);
        
        // if ($retenue->isActif() == 0) 
        // {
        //     $retenue->setActif(1);
        // } else {
        //     $retenue->setActif(0);
        // }
        
        #je prépare ma requête à la suppression
        $this->em->persist($retenue);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'enService' => "" ]);
    }
}

<?php

namespace App\Controller\Ambulance;

use App\Repository\AmbulanceRepository;
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
#[Route('/ambulance')]
class ActiverAmbulanceController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected AmbulanceRepository $ambulanceRepository
    )
    {}

    #[Route('/activer-ambulance', name: 'activer_ambulance', methods: 'POST')]
    public function activerAmbulance(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        $ambulanceId = (int)$request->request->get('ambulance_id');
        
        $ambulance = $this->ambulanceRepository->find($ambulanceId);
        
        if ($ambulance->isLibre() == 0) 
        {
            $ambulance->setLibre(1);
        } else {
            $ambulance->setLibre(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($ambulance);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'libre' => $ambulance->isLibre() ]);
    }
}

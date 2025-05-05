<?php

namespace App\Controller\Batiment;

use App\Repository\BatimentRepository;
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
#[Route('/batiment')]
class ActiverBatimentController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected BatimentRepository $batimentRepository
    )
    {}

    #[Route('/activer-batiment', name: 'activer_batiment', methods: 'POST')]
    public function activerBatiment(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        $batimentId = (int)$request->request->get('batiment_id');
        
        $batiment = $this->batimentRepository->find($batimentId);
        
        if ($batiment->isEnService() == 0) 
        {
            $batiment->setEnService(1);
        } else {
            $batiment->setEnService(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($batiment);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'enService' => $batiment->isEnService() ]);
    }
}

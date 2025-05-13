<?php

namespace App\Controller\Chambre;

use App\Repository\ChambreRepository;
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
#[Route('/chambre')]
class ActiverChambreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ChambreRepository $chambreRepository
    )
    {}

    #[Route('/activer-chambre', name: 'activer_chambre', methods: 'POST')]
    public function activerChambre(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        $chambreId = (int)$request->request->get('chambre_id');
        
        $chambre = $this->chambreRepository->find($chambreId);
        
        if ($chambre->isEnService() == 0) 
        {
            $chambre->setEnService(1);
            $chambre->setSupprime(0);
        } else {
            $chambre->setEnService(0);
            $chambre->setSupprime(1);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($chambre);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'enService' => $chambre->isEnService() ]);
    }
}

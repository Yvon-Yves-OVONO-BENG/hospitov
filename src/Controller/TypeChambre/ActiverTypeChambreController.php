<?php

namespace App\Controller\TypeChambre;

use App\Repository\TypeChambreRepository;
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
#[Route('/typeChambre')]
class ActiverTypeChambreController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected TypeChambreRepository $typeChambreRepository
    )
    {}

    #[Route('/activer-type-chambre', name: 'activer_type_chambre', methods: 'POST')]
    public function activerTypeChambre(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        $typeChambreId = (int)$request->request->get('typeChambre_id');
        
        $typeChambre = $this->typeChambreRepository->find($typeChambreId);
        
        if ($typeChambre->isEnService() == 0) 
        {
            $typeChambre->setEnService(1);
            $typeChambre->setSupprime(0);
        } else {
            $typeChambre->setEnService(0);
            $typeChambre->setSupprime(1);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($typeChambre);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'enService' => $typeChambre->isEnService() ]);
    }
}

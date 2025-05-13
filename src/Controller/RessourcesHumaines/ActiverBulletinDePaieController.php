<?php

namespace App\Controller\RessourcesHumaines;

use App\Repository\BulletinSalaireRepository;
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
#[Route('/ressourcesHumaines')]
class ActiverBulletinDePaieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private BulletinSalaireRepository $bulletinSalaireRepository
    )
    {}

    #[Route('/activer-bulletin-de-paie', name: 'activer_bulletin_de_paie', methods: 'POST')]
    public function activerBulletinDePaie(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('miseAjour', null);
        
        $bulletinId = (int)$request->request->get('bulletin_id');
        
        $bulletin = $this->bulletinSalaireRepository->find($bulletinId);
        
        if ($bulletin->ispaye() == 0) 
        {
            $bulletin->setPaye(1);
        } else {
            $bulletin->setPaye(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($bulletin);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'paye' => $bulletin->isPaye() ]);
    }
}

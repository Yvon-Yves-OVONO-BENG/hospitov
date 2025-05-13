<?php

namespace App\Controller\Modele;

use App\Repository\ModeleRepository;
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
#[Route('/modele')]
class ActiverModeleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private ModeleRepository $modeleRepository
    )
    {}

    #[Route('/activer-modele', name: 'activer_modele', methods: 'POST')]
    public function activerModele(Request $request): JsonResponse
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        $modeleId = (int)$request->request->get('modele_id');
        
        $modele = $this->modeleRepository->find($modeleId);
        
        if ($modele->isVisible() == 0) 
        {
            $modele->setVisible(1);
        } else {
            $modele->setVisible(0);
        }
        
        #je prépare ma requête à la suppression
        $this->em->persist($modele);

        #j'exécute ma requête
        $this->em->flush();

        #je retourne à la liste des catégories
        return new JsonResponse(['success' => true, 'visible' => $modele->isVisible() ]);
    }
}

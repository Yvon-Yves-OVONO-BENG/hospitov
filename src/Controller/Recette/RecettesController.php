<?php

namespace App\Controller\Recette;

use DateTime;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/recette')]
class RecettesController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        protected FactureRepository $factureRepository
    )
    {}

    #[Route('/recettes', name: 'recettes')]
    public function recettes(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }

        #je récupère les recettes des caissières du jour
        $recettes= $this->factureRepository->recettes();
        
        $nombreRecettes= $this->factureRepository->findAll();

        #je récupère toutes les recettes des caisières 
        return $this->render('recette_caissiere/recettes.html.twig', [
            'licence' => 1,
            'recettes' => $recettes,
            'nombreRecettes' => $nombreRecettes,
            'dossier' => $this->translator->trans("Recettes"),
            'route' => $this->translator->trans("Les recettes")
        ]);
    }
}

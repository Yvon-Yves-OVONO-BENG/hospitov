<?php

namespace App\Controller\ResultatsExamens;

use App\Repository\ResultatExamenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class DetailsResultatsExamenController extends AbstractController
{   
    public function __construct(
        private TranslatorInterface $translator,
        private ResultatExamenRepository $resultatExamenRepository
    )
    {}

    #[Route('/details-resultats-examen/{slug}', name: 'details_resultats_examen')]
    public function detailsResultatsExamen(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$maSession) 
        {
            $this->redirectToRoute('app_logout');
        }

        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère le resultatExamen 
        $resultatExamen = $this->resultatExamenRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        return $this->render('resultatsExamen/details_resultats_examen.html.twig', [
            'licence' => 1,
            'resultatExamen' => $resultatExamen,
            'dossier' => $this->translator->trans('Les examens'),
            'route' => $this->translator->trans("Détails de l'examen : ".$resultatExamen->getBilletDeSession()->getPatient()->getNom()),
        ]);
               
    }
}

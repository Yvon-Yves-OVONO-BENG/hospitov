<?php

namespace App\Controller\ResultatsExamens;

use App\Repository\ResultatExamenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class ResultatsExamenController extends AbstractController
{   
    public function __construct(
        private TranslatorInterface $translator,
        private ResultatExamenRepository $resultatExamenRepository
    )
    {}

    #[Route('/resultats-examen/{resultatExamen}', name: 'resultats_examen')]
    public function resultatsExamen(Request $request, string $resultatExamen): Response
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
        
        switch ($resultatExamen) 
        {
            case 'tousLesExamens':
                #tous les examens
                $examens = $this->resultatExamenRepository->findAll();
                
                return $this->render('resultatsExamen/resultats_examen.html.twig', [
                    'licence' => 1,
                    'examenFait' => 0,
                    'pasEncoreFait' => 0,
                    'tousLesExamens' => 1,
                    'dossier' => $this->translator->trans('Les examens'),
                    'route' => $this->translator->trans('Tous les examens'),
                    'examens' => $examens,
                ]);
                break;

            case 'examensFaits':
                #tous les examens faits
                $examensFaits = $this->resultatExamenRepository->findBy([
                    'realise' => 1
                ],['datePrescriptionAt' => 'ASC' ]);

                return $this->render('resultatsExamen/resultats_examen.html.twig', [
                    'licence' => 1,
                    'examenFait' => 1,
                    'pasEncoreFait' => 0,
                    'tousLesExamens' => 0,
                    'dossier' => $this->translator->trans('Les examens'),
                    'route' => $this->translator->trans('Les examens faits'),
                    'examens' => $examensFaits,
                ]);
                break;

            case 'pasEncoreFaits':
                #tous les examens pas encore faits
                $examensPasEncoreFaits = $this->resultatExamenRepository->findBy([
                    'realise' => 0
                ],['datePrescriptionAt' => 'ASC' ]);
                
                return $this->render('resultatsExamen/resultats_examen.html.twig', [
                    'licence' => 1,
                    'examenFait' => 0,
                    'pasEncoreFait' => 1,
                    'tousLesExamens' => 0,
                    'dossier' => $this->translator->trans('Les examens'),
                    'route' => $this->translator->trans('Les examens pas encore faits'),
                    'examens' => $examensPasEncoreFaits,
                ]);
                break;
            
        }

        return $this->render('resultatsExamen/resultats_examen.html.twig', [
            
        ]);
    }
}

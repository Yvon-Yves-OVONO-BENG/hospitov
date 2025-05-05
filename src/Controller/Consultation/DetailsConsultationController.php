<?php

namespace App\Controller\Consultation;

use App\Service\StrService;
use App\Form\ConsultationType;
use App\Repository\UserRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsultationRepository;
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
#[Route('/consultation')]
class DetailsConsultationController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected TranslatorInterface $translator,
        protected GenreRepository $genreRepository,
        protected ConsultationRepository $consultationRepository,
    )
    {}

    #[Route('/details-consultation/{slug}', name: 'details_consultation')]
    public function detailsConsultation(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère l'consultation à modifier
        $consultation = $this->consultationRepository->findOneBySlug([
            'slug' => $slug
        ]);

        

        #je récupère les examanes de la ligne de consultation
        $examenLigneConsultations = $consultation->getLigneConsultations();

        $examenLigneDeConsultation = [];
        foreach ($examenLigneConsultations as $examenLigneConsultation) 
        {
            $examenLigneDeConsultation[$examenLigneConsultation->getExamen()->getId()] = $examenLigneConsultation->getExamen()->getLibelle();
        }

        # j'affiche mon formulaire avec twig
        return $this->render('consultation/detailsConsultation.html.twig', [
            'licence' => 1,
            'consultation' => $consultation,
            'examenLigneDeConsultation' => $examenLigneDeConsultation,
        ]);
    }
}

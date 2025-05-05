<?php

namespace App\Controller\Consultation;

use App\Entity\ConstantsClass;
use App\Repository\ConsultationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('consultation')]

class AfficherConsultationController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private ConsultationRepository $consultationRepository
    )
    {}

    #[Route('/afficher-consultation/{consultation}/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_consultation')]
    public function afficherConsultation(Request $request, string $consultation, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if ($a == 1 || $m == 0 || $s == 0) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la mise à jour
        if ($m == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', 1);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la suppression
        
        if ($s == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', 1);
            
        }
        
        $jour = 0;
        $duJour = 0;
        $toutesLesCansultations = 0;

        if ($this->getUser() && in_array(ConstantsClass::ROLE_ADMINISTRATEUR, $this->getUser()->getRoles())) 
        {
            #je récupère toutes les consultations
            $consultations = $this->consultationRepository->findAll();
        } 
        elseif($this->getUser() && in_array(ConstantsClass::ROLE_MEDECIN, $this->getUser()->getRoles()))
        {
            switch ($consultation) 
            {
                case 'duJour':
                    $duJour = 1;
                    
                    /**
                     * @var User
                     */
                    $user = $this->getUser();

                    #je récupère les consultations du medecin
                    $consultationsDuJour = $this->consultationRepository->getConsultationsDuJour($this->userRepository->find($user->getId())->getId());

                    #j'envoie mon tableau des consultations à mon rendu twig pour affichage
                    return $this->render('consultation/afficherConsultation.html.twig', [
                        'licence' => 1,
                        'duJour' => $duJour,
                        'dossier' => $this->translator->trans('Consultations'),
                        'route' => $this->translator->trans('Consultations'),
                        'consultations' => $consultationsDuJour,
                    ]);

                    break;

                case 'toutesLesConsultations':
                    $toutesLesCansultations = 1;

                    #je récupère les consultations du medecin
                    $lesConsultations = $this->consultationRepository->findBy([
                        'medecin' => $this->getUser()
                    ]);

                    #j'envoie mon tableau des consultations à mon rendu twig pour affichage
                    return $this->render('consultation/afficherConsultation.html.twig', [
                        'licence' => 1,
                        'duJour' => 0,
                        'toutesLesCansultations' => $toutesLesCansultations,
                        'consultations' => $lesConsultations,
                        'dossier' => $this->translator->trans('Consultations'),
                        'route' => $this->translator->trans('Toutes les consultations'),
                    ]);
                    break;
                
            }

            
           
        }
        
        #j'envoie mon tableau des consultations à mon rendu twig pour affichage
        return $this->redirectToRoute('accueil');
        
    }
}

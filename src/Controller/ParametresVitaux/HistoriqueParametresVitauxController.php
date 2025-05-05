<?php

namespace App\Controller\ParametresVitaux;

use App\Repository\BilletDeSessionRepository;
use App\Repository\ParametresVitauxRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('parametres-vitaux')]

class HistoriqueParametresVitauxController extends AbstractController
{
    public function __construct(
        private PanierService $panierService,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private PatientRepository $patientRepository,
        private BilletDeSessionRepository $billetDeSessionRepository,
        private ParametresVitauxRepository $parametresVitauxRepository
    )
    {}

    #[Route('/historique-parametres-vitaux/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'historique_parametres_vitaux')]
    public function historiqueParametresVitaux(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$maSession) 
        {
            return $this->redirectToRoute('app_login');
        }

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
        
        #je récupère tous les patients 
        $patients = $this->patientRepository->findAll();

        $patient = "";
        $afficherHistorique = false;
        $historiqueParametresVitauxPatient = [];

        #je vérifie le patient existe
        if ($request->request->has('slug') ) 
        {
            #je récupère mon patient
            $patient = $this->patientRepository->findOneBy(['slug' => $request->request->get('slug') ]);
            
            $historiqueParametresVitauxPatient = $this->parametresVitauxRepository->getHistoriqueParametreVitaux($patient->getId());
            
            $afficherHistorique = true;
        } 
        
        /**
         * @var User
         */
        $user = $this->getUser();
        $historiqueParametresVitaux = $this->parametresVitauxRepository->getParametresVitauxDuJour($this->userRepository->find($user->getId())->getId());
        
        #j'envoie mon tableau des parametresVitauxs à mon rendu twig pour affichage
        return $this->render('parametresVitaux/historiqueParametresVitaux.html.twig', [
            'licence' => 1,
            'patient' => $patient,
            'patients' => $patients,
            'afficherHistorique' => $afficherHistorique,
            'historiqueParametresVitauxPatient' => $historiqueParametresVitauxPatient,
            'parametresVitaux' => $historiqueParametresVitaux,
            'dossier' => $this->translator->trans("Paramètres vitaux"),
            'route' => $this->translator->trans("Liste des paramètres vitaux"),
        ]);
    }
}

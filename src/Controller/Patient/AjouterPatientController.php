<?php

namespace App\Controller\Patient;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use App\Service\StrService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/patient')]
class AjouterPatientController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private PatientRepository $patientRepository,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
    )
    {}

    #[Route('/ajouter-patient', name: 'ajouter_patient')]
    public function ajouterPatient(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);

        $code = 0;

        #je déclare une nouvelle instace d'une sousPatient
        $patient = new Patient;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(PatientType::class, $patient);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je fabrique mon code
            $characts   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
            $characts   .= '1234567890'; 
            $code      = ''; 
    
            for($i=0;$i < 5;$i++) 
            { 
                $code .= substr($characts,rand()%(strlen($characts)),1); 
            }

            //////j'extrait le dernier patient de la table
            $dernierPatient = $this->patientRepository->findBy([],['id' => 'DESC'],1,0);

            if(!$dernierPatient)
            {
                $id = 1;
            }
            else
            {
                /////je récupère l'id du dernier produit
                $id = $dernierPatient[0]->getId();

            }

            #je met le nom de la Sous Categorie en CAPITAL LETTER
            $patient->setNom($this->strService->strToUpper($patient->getNom()))
                    ->setCode('PAT-'.$code.$id)
                    ->setEnregistrePar($this->getUser())
                    ->setDateEnregistrementAt(new DateTime('today'))
                    ->setHeureEnregistrementAt(new DateTime('now'))
                    ->setTermine(0);
            
            # je prépare ma requête avec entityManager
            $this->em->persist($patient);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Patient ajoutée avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je déclare une nouvelle instace d'une Sous Categorie
            $patient = new Patient;

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(PatientType::class, $patient);
            
        }

        #je rénitialise mon code
        $code = 0;
        $slug = 0;

        return $this->render('patient/ajouterPatient.html.twig', [
            'licence' => 1,
            'code' => $code,
            'slug' => $slug,
            'formPatient' => $form->createView(),
            'dossier' => $this->translator->trans("Patient"),
            'route' => $this->translator->trans("Ajout d'un patient"),
        ]);
    }
}

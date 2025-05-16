<?php

namespace App\Controller\Hospitalisation;

use App\Entity\Hospitalisation;
use App\Form\HospitalisationType;
use App\Repository\BilletDeSessionRepository;
use App\Repository\ConsultationRepository;
use App\Repository\ParametresVitauxRepository;
use App\Service\StrService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('hospitalisation')]
class AjouterHospitalisationController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ConsultationRepository $consultationRepository,
        private BilletDeSessionRepository $billetDeSessionRepository,
        private ParametresVitauxRepository $parametresVitauxRepository,
    )
    {}

    #[Route('/ajouter-hospitalisation', name: 'ajouter_hospitalisation')]
    public function ajouterHospitalisation(Request $request): Response
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

        $slug = 0;

        #je déclare une nouvelle instace d'une hospitalisation
        $hospitalisation = new Hospitalisation;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(HospitalisationType::class, $hospitalisation);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('envoieMessage')->getValue();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('envoieMessage', $csrfTokenFormulaire))) 
            {
                #je modifie l'état du lit
                $lit = $hospitalisation->getLit();
                $lit->setDisponible(1);

                #je récupère le patient
                $patient = $form->get('patient')->getData();

                #je récupère le dernier billet de sessions
                $dernierBilletDeSession = $this->billetDeSessionRepository->dernierBilletDeSession($patient);

                if ($dernierBilletDeSession) 
                {
                    #je récupère les paramètres vitaux
                    $parametresVitaux = $this->parametresVitauxRepository->findOneBy([
                        'billetDeSession' => $dernierBilletDeSession
                    ]);
                    
                    #je récupère la consultation associée 
                    $consultationInitiale = $this->consultationRepository->findOneBy([
                        'parametresVitaux' => $parametresVitaux 
                    ]);

                    #je met le nom de la hospitalisation en CAPITAL LETTER
                    $hospitalisation->setSlug(uniqid('', true))
                    ->setConsultationInitiale($consultationInitiale)
                    ->setDateEntreeAt(new DateTime('today'))
                    ->setHeureEntreeAt(new DateTime('now'))
                    ->setEnregistrePar($this->getUser());
                }
                
                
                # je prépare ma requête avec entityManager
                $this->em->persist($lit);
                $this->em->persist($hospitalisation);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Hospitalisation enregistrée avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je déclare une nouvelle instace d'une hospitalisation
                $hospitalisation = new Hospitalisation;

                #je crée mon formulaire et je le lie à mon instance
                $form = $this->createForm(HospitalisationType::class, $hospitalisation);
            }
            else 
            {
                /**
                 * @var User
                 */
                $user = $this->getUser();
                $user->setEtat(1);

                $this->em->persist($user);
                $this->em->flush();

                return $this->redirectToRoute('accueil', ['b' => 1 ]);

            }
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('hospitalisation/ajouterHospitalisation.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'csrfToken' => $csrfToken,
            'formHospitalisation' => $form->createView(),
            'dossier' => $this->translator->trans('Hospitalisation'),
            'route' => $this->translator->trans("Enregistrement d'une hospitalisation"),
        ]);
    }
}

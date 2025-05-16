<?php

namespace App\Controller\Consultation;

use App\Entity\User;
use App\Form\ConsultationType;
use App\Service\StrService;
use App\Repository\UserRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsultationRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/consultation')]
class ModifierConsultationController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private GenreRepository $genreRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ConsultationRepository $consultationRepository,
    )
    {}

    #[Route('/modifier-consultation/{slug}', name: 'modifier_consultation')]
    public function modifierConsultation(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$this->getUser()) 
        {
            return $this->redirectToRoute('app_logout');
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère l'consultation à modifier
        $consultation = $this->consultationRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #genre 
        $genres = $this->genreRepository->findAll();

        #medecins/generalistes
        $medecins = $this->userRepository->medecinsSpecialiste();

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ConsultationType::class, $consultation);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('parametresVitaux')->getValue();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('parametresVitaux', $csrfTokenFormulaire))) 
            {
                #je met le nom de la consultation en CAPITAL LETTER
                $consultation->setDateConsultationAt(new DateTime('now'))
                ->setHeureConsultationAt(new DateTime('now'))
                ;
                

                # je prépare ma requête avec entityManager
                $this->em->persist($consultation);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Consulation enregistrée avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je retourne à la liste des consultations
                return $this->redirectToRoute('afficher_consultation', [ 'consultation' => 'toutesLesConsultations', 'm' => 1 ]);
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

                return $this->redirectToRoute('app_logout');
            }
        }

        # j'affiche mon formulaire avec twig
        return $this->render('consultation/consultation.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'genres' => $genres,
            'medecins' => $medecins,
            'csrfToken' => $csrfToken,
            'consultation' => $consultation,
            'formConsultation' => $form->createView(),
            'dossier' => $this->translator->trans('Consultations'),
            'route' => $this->translator->trans('Enregistrement de la consultation de : '.$consultation->getParametresVitaux()->getBilletDeSession()->getPatient()->getNom()),
        ]);
    }
}

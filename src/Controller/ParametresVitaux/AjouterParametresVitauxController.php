<?php

namespace App\Controller\ParametresVitaux;

use App\Entity\ParametresVitaux;
use App\Service\StrService;
use App\Entity\ConstantsClass;
use App\Entity\Consultation;
use App\Form\ParametresVitauxType;
use App\Repository\BilletDeSessionRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[IsGranted('ROLE_USER')]
#[Route('parametres-vitaux')]
class AjouterParametresVitauxController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private BilletDeSessionRepository $billetDeSessionRepository,
    )
    {}

    #[Route('/ajouter-parametres-vitaux/{slugBilletDeSession}', name: 'ajouter_parametres_vitaux')]
    public function ajouterParametresVitaux(Request $request, $slugBilletDeSession): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if (!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une parametresVitaux
        $parametresVitaux = new ParametresVitaux;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ParametresVitauxType::class, $parametresVitaux);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('parametresVitaux')->getValue();

        #je recupere le billet de session à setter 
        $billetDeSession = $this->billetDeSessionRepository->findOneBy(['slug' => $slugBilletDeSession ]);

        #je sélectionne les médecins
        $medecins = $this->userRepository->medecinsSpecialiste();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('parametresVitaux', $csrfTokenFormulaire))) 
            {
                $billetDeSession->setEtat(1);

                #je met le nom de la parametresVitaux en CAPITAL LETTER
                $parametresVitaux->setSlug(uniqid('', true))
                ->setBilletDeSession($billetDeSession)
                ->setDatePriseAt(new DateTime('now'))
                ->setInfirmier($this->getUser());

                #je cré une instance pour la consultation
                $consultation = new Consultation;

                $consultation->setSlug(uniqid('', true))
                            ->setMedecin($this->userRepository->find($request->request->get('medecinId')))
                            ->setParametresVitaux($parametresVitaux)
                            ->setSupprime(0)
                            ;
                
                # je prépare ma requête avec entityManager
                $this->em->persist($billetDeSession);
                $this->em->persist($parametresVitaux);
                $this->em->persist($consultation);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Paramètres vitaux enregistrés avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                #je déclare une nouvelle instace d'une parametresVitaux
                $parametresVitaux = new ParametresVitaux;

                #je crée mon formulaire et je le lie à mon instance
                $form = $this->createForm(ParametresVitauxType::class, $parametresVitaux);
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

        return $this->render('parametresVitaux/ajouterParametresVitaux.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'medecins' => $medecins,
            'csrfToken' => $csrfToken,
            'billetDeSession' => $billetDeSession,
            'formParametresVitaux' => $form->createView(),
            'dossier' => $this->translator->trans("Parametres vitaux"),
            'route' => $this->translator->trans("Ajout des paramètres vitaux"),
        ]);
    }
}

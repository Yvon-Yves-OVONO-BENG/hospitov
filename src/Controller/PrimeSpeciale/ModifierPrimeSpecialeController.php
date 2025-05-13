<?php

namespace App\Controller\PrimeSpeciale;

use App\Form\PrimeSpecialeType;
use App\Service\StrService;
use App\Repository\PrimeSpecialeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


#[IsGranted('ROLE_USER')]
#[Route('/primeSpeciale')]
class ModifierPrimeSpecialeController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private PrimeSpecialeRepository $primeSpecialeRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/modifier-prime-speciale/{slug}', name: 'modifier_prime_speciale')]
    public function modifierPrimeSpeciale(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        $maSession->set('miseAjour', null);

        #je récupère l'primeSpeciale à modifier
        $primeSpeciale = $this->primeSpecialeRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(PrimeSpecialeType::class, $primeSpeciale);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('primeSpeciale')->getValue();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('primeSpeciale', $csrfTokenFormulaire))) 
            {

                #je met le nom de la primeSpeciale en CAPITAL LETTER
                $primeSpeciale->setLibelle($this->strService->strToUpper($primeSpeciale->getLibelle()))
                        ->setSlug(uniqid("", true))
                ;

                # je prépare ma requête avec entityManager
                $this->em->persist($primeSpeciale);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Prime spéciale mise à jour avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('misAjour', 1);
                
                #je retourne à la liste des primeSpeciales
                return $this->redirectToRoute('afficher_prime_speciale', [ 'm' => 1 ]);
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

        # j'affiche mon formulaire avec twig
        return $this->render('primeSpeciale/ajouterPrimeSpeciale.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'primeSpeciale' => $primeSpeciale,
            'csrfToken' => $csrfToken,
            'formPrimeSpeciale' => $form->createView(),
            'route' => $this->translator->trans("Modification du primeSpeciale : ".$primeSpeciale->getLibelle()),
            'dossier' => $this->translator->trans("Les primeSpeciales"),
        ]);
    }
}

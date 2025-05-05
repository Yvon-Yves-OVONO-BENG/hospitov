<?php

namespace App\Controller\ParametresVitaux;

use App\Form\ParametresVitauxType;
use App\Service\StrService;
use App\Repository\ParametresVitauxRepository;
use App\Repository\UserRepository;
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
#[Route('/parametres-vitaux')]
class ModifierParametresVitauxController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private ParametresVitauxRepository $parametresVitauxRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/modifier-parametres-vitaux/{slug}', name: 'modifier_parametres_vitaux')]
    public function modifierParametresVitaux(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        if (!$maSession) 
        {
            return $this->redirectToRoute('app_login');
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
    
        #je récupère l'parametresVitaux à modifier
        $parametresVitaux = $this->parametresVitauxRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ParametresVitauxType::class, $parametresVitaux);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('parametresVitaux')->getValue();

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
                # je prépare ma requête avec entityManager
                $this->em->persist($parametresVitaux);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Paramètres vitaux mis à jour avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('misAjour', 1);
                
                #je retourne à la liste des parametresVitauxs
                return $this->redirectToRoute('afficher_parametres_vitaux', ['parametre' => 'tousLesParametres', 'm' => 1 ]);
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
        return $this->render('parametresVitaux/ajouterParametresVitaux.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'medecins' => $medecins,
            'csrfToken' => $csrfToken,
            'parametresVitaux' => $parametresVitaux,
            'formParametresVitaux' => $form->createView(),
            'dossier' => $this->translator->trans("Parametres vitaux"),
            'route' => $this->translator->trans("Modification des paramètres de : ").$parametresVitaux->getBilletDeSession()->getPatient()->getCode(). " - ".$parametresVitaux->getBilletDeSession()->getPatient()->getNom(),
        ]);
    }
}

<?php

namespace App\Controller\Profil;

use App\Service\StrService;
use App\Form\AjoutUtilisateurType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/profil')]
class EditerProfilController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private UserRepository $profilRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
    )
    {}
    
    #[Route('/editer-profil/{pe}', name: 'editer_profil')]
    public function editerProdil(Request $request, $pe = 0): Response
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

        #je récupère le profil à modifier
        /**
         * @var user
         */
        $user = $this->getUser();

        $user = $this->userRepository->find($user->getId());
        
        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(AjoutUtilisateurType::class, $user);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je met le nom de l'utilisateur en CAPITAL LETTER
            $user->setNom($this->strService->strToUpper($user->getNom()));
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );

            # je prépare ma requête avec entityManager
            $this->em->persist($user);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Profil mis à jour avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            
            
            #je redirige vers l'affichage du profil
            return $this->redirectToRoute('afficher_profil', ['m' => 1]);
            
        }

        return $this->render('profil/editer_profil.html.twig', [
            'pe' => 1,
            'licence' => 1,
            'formUtilisateur' => $form->createView(),
            'dossier' => $this->translator->trans("Profil"),
            'route' => $this->translator->trans("Edition de mon profil"),
            
        ]);
    }
}

<?php

namespace App\Controller\Utilisateur;

use App\Service\StrService;
use App\Entity\ConstantsClass;
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
class ModifierUtilisateurController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private UserRepository $profilRepository,
        private TranslatorInterface $translator,
    )
    {}
    
    #[Route('/modifier-utilisateur/{slug}', name: 'modifier_utilisateur')]
    public function modifierUtilisateur(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        #je déclare une nouvelle instace d'un utilisateur
        $utilisateur = $this->userRepository->findOneBySlug([
            'slug' => $slug
        ]);
        
        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(AjoutUtilisateurType::class, $utilisateur);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je fabrique mon slug
            $characts    = 'abcdefghijklmnopqrstuvwxyz#{};()';
            $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ#{};()';	
            $characts   .= '1234567890'; 
            $slug      = ''; 
    
            for($i=0;$i < 11;$i++) 
            { 
                $slug .= substr($characts,rand()%(strlen($characts)),1); 
            }

            //////j'extrait le dernier utilisateur de la table
            $derniereUtilisateur = $this->userRepository->findBy([],['id' => 'DESC'],1,0);

            /////je récupère l'id du sernier utilisateur
            $id = $derniereUtilisateur[0]->getId();

            #je met le nom de l'utilisateur en CAPITAL LETTER
            $utilisateur->setNom($this->strService->strToUpper($utilisateur->getNom()))
                    ->setSlug($slug.$id)
            ;
            
            #je hash le mot de passe
            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $form->getData()->getPassword()
                )
            );

            # je récupère le type utlisateur
            $typeUtilisateur = $form->getData()->getTypeUtilisateur()->getTypeUtilisateur();

            #selon le type utilisateur je set le rôle
            switch ($typeUtilisateur) 
            {
                case ConstantsClass::ADMINISTRATEUR:
                    $utilisateur->setRoles([ConstantsClass::ROLE_ADMINISTRATEUR]);
                    break;

                case ConstantsClass::DIRECTEUR:
                    $utilisateur->setRoles([ConstantsClass::ROLE_DIRECTEUR]);
                    break;

                case ConstantsClass::PHARMACIEN:
                    $utilisateur->setRoles([ConstantsClass::ROLE_PHARMACIEN]);
                    break;

                case ConstantsClass::REGIES_DES_RECETTES:
                    $utilisateur->setRoles([ConstantsClass::ROLE_REGIES_DES_RECETTES]);
                    break;

                case ConstantsClass::SECRETAIRE:
                    $utilisateur->setRoles([ConstantsClass::ROLE_SECRETAIRE]);
                    break;

                case ConstantsClass::CAISSIERE:
                    $utilisateur->setRoles([ConstantsClass::ROLE_CAISSIERE]);
                    break;
                    
            }

            # je prépare ma requête avec entityManager
            $this->em->persist($utilisateur);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Utilisateur mise à jour avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je retourne à la liste des categories
            return $this->redirectToRoute('afficher_utilisateur', [ 'm' => 1 ]);
            
        }

        return $this->render('utilisateur/ajouterUtilisateur.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'utilisateur' => $utilisateur,
            'dossier' => $this->translator->trans('Personnel'),
            'route' => $this->translator->trans('Modification de '. $utilisateur->getNom()),
            'formUtilisateur' => $form->createView(),
        ]);
    }
}

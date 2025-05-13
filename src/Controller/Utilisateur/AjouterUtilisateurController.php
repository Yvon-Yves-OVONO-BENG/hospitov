<?php

namespace App\Controller\Utilisateur;

use App\Entity\ConstantsClass;
use App\Entity\Profil;
use App\Entity\User;
use App\Form\AjoutUtilisateurType;
use App\Repository\UserRepository;
use App\Service\StrService;
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
#[Route('utilisateur')]
class AjouterUtilisateurController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
    )
    {}
    
    #[Route('/ajout-utilisateur', name: 'ajouter_utilisateur')]
    public function ajoutUtilisateur(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
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

        $slug = 0;

        #je déclare une nouvelle instace d'un utilisateur
        $utilisateur = new User;

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
                    ->setEtat(0)
                    ->setSupprime(0)
                    ->setStatut(0)
                    ->setPhoto(ConstantsClass::NOM_PHOTO)
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

                case ConstantsClass::CAISSIERE_ACCUEIL:
                    $utilisateur->setRoles([ConstantsClass::ROLE_CAISSIERE_ACCUEIL]);
                    break;
                
                case ConstantsClass::CAISSIERE_PHARMACIE:
                    $utilisateur->setRoles([ConstantsClass::ROLE_CAISSIERE_PHARMACIE]);
                    break;

                case ConstantsClass::PARAMETRES_VITAUX:
                    $utilisateur->setRoles([ConstantsClass::ROLE_PARAMETRES_VITAUX]);
                    break;

                case ConstantsClass::MEDECIN:
                    $utilisateur->setRoles([ConstantsClass::ROLE_MEDECIN]);
                    break;

                case ConstantsClass::LABORANTIN:
                    $utilisateur->setRoles([ConstantsClass::ROLE_LABORANTIN]);
                    break;

                case ConstantsClass::HOSPITALISATION:
                    $utilisateur->setRoles([ConstantsClass::ROLE_HOSPITALISATION]);
                    break;
    
                    
            }

            $typeUtilisateu = $utilisateur->getTypeUtilisateur();

            # je prépare ma requête avec entityManager
            $this->em->persist($utilisateur);

            #j'exécute ma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Utilisateur ajoutée avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            
            
            #je déclare une nouvelle instace d'une utilisateur
            $utilisateur = new User;

            $utilisateur->setTypeUtilisateur($typeUtilisateu);

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(AjoutUtilisateurType::class, $utilisateur);
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('utilisateur/ajouterUtilisateur.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formUtilisateur' => $form->createView(),
            'dossier' => $this->translator->trans("Personnel"),
            'route' => $this->translator->trans("Ajout d'un personnel"),
        ]);
    }
}

<?php

namespace App\Controller\TypeUtilisateur;

use App\Entity\TypeUtilisateur;
use App\Form\TypeUtilisateurType;
use App\Service\StrService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('typeUtilisateur')]
class AjouterTypeUtilisateurController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
    )
    {}

    #[Route('/ajouter-typeUtilisateur', name: 'ajouter_typeUtilisateur')]
    public function ajouterTypeUtilisateur(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une typeUtilisateur
        $typeUtilisateur = new TypeUtilisateur;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(TypeUtilisateurType::class, $typeUtilisateur);

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

            #je met le nom de la typeUtilisateur en CAPITAL LETTER
            $typeUtilisateur->setTypeUtilisateur($this->strService->strToUpper($typeUtilisateur->getTypeUtilisateur()))
                    ->setSlug($slug)
            ;
            
            # je prépare ma requête avec entityManager
            $this->em->persist($typeUtilisateur);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Type personnel ajouté avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je déclare une nouvelle instace d'une typeUtilisateur
            $typeUtilisateur = new TypeUtilisateur;

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(TypeUtilisateurType::class, $typeUtilisateur);
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('typeUtilisateur/ajouterTypeUtilisateur.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'formTypeUtilisateur' => $form->createView(),
            'dossier' => $this->translator->trans("Type personnel"),
            'route' => $this->translator->trans("Ajout d'un type personnel"),
        ]);
    }
}

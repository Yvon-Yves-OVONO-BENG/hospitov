<?php

namespace App\Controller\Specialite;

use App\Form\SpecialiteType;
use App\Service\StrService;
use App\Repository\SpecialiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/specialite')]
class ModifierSpecialiteController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private SpecialiteRepository $specialiteRepository,
    )
    {}

    #[Route('/modifier-specialite/{slug}', name: 'modifier_specialite')]
    public function modifierSpecialite(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        #je récupère l'specialite à modifier
        $specialite = $this->specialiteRepository->findOneBySlug([
            'slug' => $slug
        ]);

        
        #medecins
        $medecins = $this->userRepository->medecinsSpecialiste();

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(SpecialiteType::class, $specialite);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);

        #je fabrique mon slug
        $characts    = 'abcdefghijklmnopqrstuvwxyz#{};()';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ#{};()';	
        $characts   .= '1234567890'; 
        $slug      = ''; 

        for($i=0;$i < 11;$i++) 
        { 
            $slug .= substr($characts,rand()%(strlen($characts)),1); 
        }

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je met le nom de la specialite en CAPITAL LETTER
            $specialite->setSpecialite($this->strService->strToUpper($specialite->getSpecialite()))
                    ->setSlug($slug)
            ;

            # je prépare ma requête avec entityManager
            $this->em->persist($specialite);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Département / Spécialité mise à jour avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('misAjour', 1);
            
            #je retourne à la liste des specialites
            return $this->redirectToRoute('afficher_specialite', [ 'm' => 1 ]);
        }

        # j'affiche mon formulaire avec twig
        return $this->render('specialite/ajouterSpecialite.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'medecins' => $medecins,
            'specialite' => $specialite,
            'formSpecialite' => $form->createView(),
            'dossier' => $this->translator->trans("Département"),
            'route' => $this->translator->trans("Modification de : ").$specialite->getSpecialite(),
        ]);
    }
}

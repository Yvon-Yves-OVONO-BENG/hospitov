<?php

namespace App\Controller\Specialite;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\UserRepository;
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
#[Route('specialite')]
class AjouterSpecialiteController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected TranslatorInterface $translator,
    )
    {}

    #[Route('/ajouter-specialite', name: 'ajouter_specialite')]
    public function ajouterSpecialite(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une specialite
        $specialite = new Specialite;

        #medecins
        $medecins = $this->userRepository->medecinsSpecialiste();

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(SpecialiteType::class, $specialite);

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
            
            if ($request->request->get('idMedecin')) 
            {
                #je récupère l'id du medecin
                $idMedecin = $request->request->get('idMedecin');
                
                #je recupère le  médecin en question
                $medecin = $this->userRepository->find($idMedecin);

                $specialite->setChefDeDepartement($medecin);
            }
            
            
            #je met le nom de la specialite en CAPITAL LETTER
            $specialite->setSpecialite($this->strService->strToUpper($specialite->getSpecialite()))
                    ->setSlug($slug)
                    ->setSupprime(0);
            
            # je prépare ma requête avec entityManager
            $this->em->persist($specialite);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Spécialité ajouté avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je déclare une nouvelle instace d'une specialite
            $specialite = new Specialite;

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(SpecialiteType::class, $specialite);
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('specialite/ajouterSpecialite.html.twig', [
            'slug' => $slug,
            'dossier' => $this->translator->trans("Département"),
            'route' => $this->translator->trans("Ajout d'un Département / d'une Spécialité"),
            'medecins' => $medecins,
            'licence' => 1,
            'specialite' => 0,
            'formSpecialite' => $form->createView(),
        ]);
    }
}

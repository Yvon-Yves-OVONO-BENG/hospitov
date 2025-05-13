<?php

namespace App\Controller\Hospitalisation;

use App\Form\HospitalisationType;
use App\Service\StrService;
use App\Repository\HospitalisationRepository;
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
#[Route('/hospitalisation')]
class ModifierHospitalisationController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private HospitalisationRepository $hospitalisationRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/modifier-hospitalisation/{slug}', name: 'modifier_hospitalisation')]
    public function modifierHospitalisation(Request $request, string $slug): Response
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

        #je récupère l'hospitalisation à modifier
        $hospitalisation = $this->hospitalisationRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(HospitalisationType::class, $hospitalisation);

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

        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('envoieMessage')->getValue();

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('envoieMessage', $csrfTokenFormulaire))) 
            {

                #je met le nom de la hospitalisation en CAPITAL LETTER
                $hospitalisation->setHospitalisation($this->strService->strToUpper($hospitalisation->getHospitalisation()))
                        ->setSlug($slug)
                ;

                # je prépare ma requête avec entityManager
                $this->em->persist($hospitalisation);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Catégorie mise à jour avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                

                #je retourne à la liste des hospitalisations
                return $this->redirectToRoute('afficher_hospitalisation', [ 'm' => 1 ]);
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
        return $this->render('hospitalisation/ajouterHospitalisation.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'hospitalisation' => $hospitalisation,
            'csrfToken' => $csrfToken,
            'formHospitalisation' => $form->createView(),
        ]);
    }
}

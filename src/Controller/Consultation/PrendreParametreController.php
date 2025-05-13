<?php

namespace App\Controller\Consultation;

use App\Entity\Consultation;
use App\Form\ParametreType;
use App\Repository\GenreRepository;
use App\Repository\UserRepository;
use DateTime;
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
#[Route('consultation')]
class PrendreParametreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        private GenreRepository $genreRepository,
    )
    {}

    #[Route('/prendre-parametre', name: 'prendre_parametre')]
    public function prendreParametre(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #genre 
        $genres = $this->genreRepository->findAll();

        #medecins/generalistes
        $medecins = $this->userRepository->medecins();
        
        #je déclare une nouvelle instace d'une consultation
        $consultation = new Consultation;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ParametreType::class, $consultation);
        
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
    
            for($i=0;$i < 12;$i++) 
            { 
                $slug .= substr($characts,rand()%(strlen($characts)),1); 
            }
            
            #le medecin qui va consulter
            $consultePar = $this->userRepository->find((int)$request->request->get('idUser'));

            #le genre
            $genre = $this->genreRepository->find((int)$request->request->get('idGenre'));

            $patient = $form->getData()->getPatient();

            $patient->setGenre($genre);
            $this->em->persist($patient);

            #je met le nom de la consultation en CAPITAL LETTER
            $consultation->setSlug($slug)
                    ->setSupprime(0)
                    ->setDateParametrePrisAt(new DateTime('now'))
                    ->setHeureParametreAt(new DateTime('now'))
                    ->setParametrePrisPar($this->getUser())
                    ->setConsultePar($consultePar);
            ;
            
            # je prépare ma requête avec entityManager
            $this->em->persist($consultation);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Consultation ajoutée avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je déclare une nouvelle instace d'une consultation
            $consultation = new Consultation;

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(ParametreType::class, $consultation);
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('consultation/prendreParametre.html.twig', [
            'slug' => $slug,
            'genres' => $genres,
            'medecins' => $medecins,
            'consultation' => [],
            'licence' => 1,
            'formConsultation' => $form->createView(),
            'dossier' => $this->translator->trans("Consultation"),
            'route' => $this->translator->trans("Prise des paramètres"),
        ]);
    }
}

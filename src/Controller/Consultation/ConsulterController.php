<?php

namespace App\Controller\Consultation;

use App\Entity\Consultation;
use App\Entity\LigneConsultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use App\Repository\GenreRepository;
use App\Repository\LigneConsultationRepository;
use App\Repository\ProduitRepository;
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
class ConsulterController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected TranslatorInterface $translator,
        protected GenreRepository $genreRepository,
        protected ProduitRepository $produitRepository,
        protected ConsultationRepository $consultationRepository,
        protected LigneConsultationRepository $ligneConsultationRepository,
    )
    {}

    #[Route('/consulter/{slug}', name: 'consulter')]
    public function ajouterConsultation(Request $request, string $slug): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        #je récupère la consultation à faire
        $consultation = $this->consultationRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je récupère les examens
        $examens = $this->produitRepository->findBy([
            'examen' => 1
        ]);
       
        $examensChoices = [];

        foreach ($examens as $examen) 
        {
            $examensChoices[$examen->getLibelle()] = $examen->getId();
        }

        #je récupère les examanes de la ligne de consultation
        $examenLigneConsultations = $consultation->getLigneConsultations();

        
        $examenLigneDeConsultation = [];
        foreach ($examenLigneConsultations as $examenLigneConsultation) 
        {
            $examenLigneDeConsultation[$examenLigneConsultation->getExamen()->getId()] = $examenLigneConsultation->getExamen()->getLibelle();
        }

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ConsultationType::class, $consultation, [
            'examens' => $examensChoices
        ]);
        
        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère les id des Examens
            $selectedExamens = $form->get('examens')->getData();

            
            if ($selectedExamens) 
            {
                #pour chaque id je récupère l'examen pour enregistrer dans la table lignConsultation
                foreach ($selectedExamens as $idExamen) 
                {
                    $ligneDeConsultation = new LigneConsultation;

                    $examen = $this->produitRepository->find($idExamen);

                    $ligneDeConsultation->setConsultation($consultation)
                    ->setExamen($examen);
                    
                    $this->em->persist($ligneDeConsultation);
                }

            }
            
            if ($form->getData()->getFichierResultatsExamenFile()) 
            {
                $consultation->setDateResultatExamenAt(new DateTime('now'))
                ->setHeureResultatExamenAt(new DateTime('now'))
                ;
            }

            #je met le nom de la consultation en CAPITAL LETTER
            $consultation->setSlug($slug)
                    ->setSupprime(0)
                    ->setDateConsultationAt(new DateTime('now'))
                    ->setHeureConsultationAt(new DateTime('now'));
            
            # je prépare ma requête avec entityManager
            $this->em->persist($consultation);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Consultation enregistrée avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je retourne à l'affichage des ses consultation
            return $this->redirectToRoute('mes_consultations_du_jour', ['m' => 1 ]);
        
        }

        return $this->render('consultation/consulter.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'examens' => $examens,
            'consultation' => $consultation,
            'formConsultation' => $form->createView(),
            'examenLigneDeConsultation' => $examenLigneDeConsultation,
        ]);
    }
}

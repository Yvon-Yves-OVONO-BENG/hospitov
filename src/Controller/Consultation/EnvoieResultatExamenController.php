<?php

namespace App\Controller\Consultation;

use App\Entity\Consultation;
use App\Entity\LigneConsultation;
use App\Form\ConsultationType;
use App\Form\EnvoieFicherExamenType;
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
class EnvoieResultatExamenController extends AbstractController
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

    #[Route('/envoie-resultat-examen/{slug}', name: 'envoie_resultat_examen')]
    public function envoieResultatExamen(Request $request, string $slug): Response
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

        #je récupère les examanes de la ligne de consultation
        $examenLigneConsultations = $consultation->getLigneConsultations();

        $examenLigneDeConsultation = [];
        foreach ($examenLigneConsultations as $examenLigneConsultation) 
        {
            $examenLigneDeConsultation[$examenLigneConsultation->getExamen()->getId()] = $examenLigneConsultation->getExamen()->getLibelle();
        }

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(EnvoieFicherExamenType::class, $consultation);
        
        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $consultation->setDateResultatExamenAt(new DateTime('now'))
            ->setHeureResultatExamenAt(new DateTime('now'))
            ->setLaborantin($this->getUser())
            ;
            
            # je prépare ma requête avec entityManager
            $this->em->persist($consultation);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Résultat examens envoyés avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je retourne à l'affichage des ses consultation
            return $this->redirectToRoute('examen_du_jour', ['m' => 1 ]);
        
        }

        return $this->render('consultation/envoieResultatExamen.html.twig', [
            'licence' => 1,
            'slug' => $slug,
            'consultation' => $consultation,
            'formResultats' => $form->createView(),
            'examenLigneDeConsultation' => $examenLigneDeConsultation,
        ]);
    }
}

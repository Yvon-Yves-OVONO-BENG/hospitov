<?php

namespace App\Controller\ResultatsExamens;

use App\Entity\FichierResultatExamen;
use App\Form\ResultatExamenType;
use DateTime;
use App\Service\StrService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResultatExamenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class EnvoyerResultatController extends AbstractController
{
    public function __construct(
        private StrService $strService,
        private EntityManagerInterface $em,
        private TranslatorInterface $translator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private ResultatExamenRepository $resultatExamenRepository,
    )
    {}

    #[Route('/envoyer-resultat/{slug}', name: 'envoyer_resultat')]
    public function ajouterRetenue(Request $request, $slug): Response
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
        $maSession->set('misAjour', null);

        #je déclare une nouvelle instace d'un resultat examen
        $resultatExamen = $this->resultatExamenRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #un nouveau fichier
        $fichierResultatExamen = new FichierResultatExamen;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(ResultatExamenType::class, $fichierResultatExamen);

        #je demande à mon formulaire de récupérer les donnéesqui sont dans le POST avec la $request
        $form->handleRequest($request);
        
        # je crée mon CSRF pour sécuriser mes formulaires
        $csrfToken = $this->csrfTokenManager->getToken('envoieResultat')->getValue();

        $maSession->set('csrfToken', $csrfToken);

        #je teste si mon formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            #je récupère le csrf du formulaire
            $csrfTokenFormulaire = $request->request->get('csrfToken');

            if ($this->csrfTokenManager->isTokenValid(
                new CsrfToken('envoieResultat', $csrfTokenFormulaire))) 
            {
                #je met le nom de la retenue en CAPITAL LETTER
                $resultatExamen->setRealise(1)
                ->setDateResultatAt(new DateTime('today'))
                ->setHeureResultatAt(new DateTime('now'))
                ->setLaborantin($this->getUser())
                ;

                $fichierResultatExamen->setResultatExamen($resultatExamen);
                
                # je prépare ma requête avec entityManager
                $this->em->persist($resultatExamen);
                $this->em->persist($fichierResultatExamen);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Résultat envoyé avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                $this->redirectToRoute('resultats_examen', ['resultatExamen' => 'pasEncoreFaits' ]);
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

                return $this->redirectToRoute('app_logout');

            }
            
        }

        return $this->render('resultatsExamen/envoyer_resultats_examen.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'csrfToken' => $csrfToken,
            'resultatExamen' => $resultatExamen,
            'patient' => $resultatExamen->getBilletDeSession()->getPatient()->getNom(),
            'formResultatExamen' => $form->createView(),
            'dossier' => $this->translator->trans("Les résultats"),
            'route' => $this->translator->trans("Envoie des résultats de : ".$resultatExamen->getBilletDeSession()->getPatient()->getNom()),
        ]);
    }
}

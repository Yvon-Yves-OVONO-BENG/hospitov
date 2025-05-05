<?php

namespace App\Controller\Categorie;

use App\Form\CategorieType;
use App\Service\StrService;
use App\Repository\CategorieRepository;
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
#[Route('/categorie')]
class ModifierCategorieController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
        protected CategorieRepository $categorieRepository,
        protected CsrfTokenManagerInterface $csrfTokenManager,
    )
    {}

    #[Route('/modifier-categorie/{slug}', name: 'modifier_categorie')]
    public function modifierCategorie(Request $request, string $slug): Response
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

        #je récupère l'categorie à modifier
        $categorie = $this->categorieRepository->findOneBySlug([
            'slug' => $slug
        ]);

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(CategorieType::class, $categorie);

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

                #je met le nom de la categorie en CAPITAL LETTER
                $categorie->setCategorie($this->strService->strToUpper($categorie->getCategorie()))
                        ->setSlug($slug)
                ;

                # je prépare ma requête avec entityManager
                $this->em->persist($categorie);

                #j'exécutebma requête
                $this->em->flush();

                #j'affiche le message de confirmation d'ajout
                $this->addFlash('info', $this->translator->trans('Catégorie mise à jour avec succès !'));

                #j'affecte 1 à ma variable pour afficher le message
                $maSession->set('ajout', 1);
                
                

                #je retourne à la liste des categories
                return $this->redirectToRoute('afficher_categorie', [ 'm' => 1 ]);
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
        return $this->render('categorie/ajouterCategorie.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'categorie' => $categorie,
            'csrfToken' => $csrfToken,
            'formCategorie' => $form->createView(),
        ]);
    }
}

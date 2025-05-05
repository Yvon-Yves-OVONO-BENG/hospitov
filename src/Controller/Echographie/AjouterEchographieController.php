<?php

namespace App\Controller\Echographie;

use App\Entity\Produit;
use App\Service\StrService;
use App\Form\EchographieType;
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
#[Route('echographie')]
class AjouterEchographieController extends AbstractController
{
    public function __construct(
        protected StrService $strService,
        protected EntityManagerInterface $em,
        protected TranslatorInterface $translator,
    )
    {}

    #[Route('/ajouter-echographie', name: 'ajouter_echographie')]
    public function ajouterEchographie(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);

        $slug = 0;

        #je déclare une nouvelle instace d'une echographie
        $echographie = new Produit;

        #je crée mon formulaire et je le lie à mon instance
        $form = $this->createForm(EchographieType::class, $echographie);

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

            #je met le nom de la echographie en CAPITAL LETTER
            $echographie->setLibelle($this->strService->strToUpper($echographie->getLibelle()))
                    ->setSlug($slug)
                    ->setBilletDeSession(0)
                    ->setCarnet(0)
                    ->setEchographie(1)
                    ->setExamen(0)
                    ->setKit(0)
                    ->setRadio(0)
                    ->setRetire(0)
                    ->setSupprime(0)
            ;
            
            # je prépare ma requête avec entityManager
            $this->em->persist($echographie);

            #j'exécutebma requête
            $this->em->flush();

            #j'affiche le message de confirmation d'ajout
            $this->addFlash('info', $this->translator->trans('Echographie ajoutée avec succès !'));

            #j'affecte 1 à ma variable pour afficher le message
            $maSession->set('ajout', 1);
            
            #je déclare une nouvelle instace d'une echographie
            $echographie = new Produit;

            #je crée mon formulaire et je le lie à mon instance
            $form = $this->createForm(EchographieType::class, $echographie);
            
        }

        #je rénitialise mon slug
        $slug = 0;

        return $this->render('echographie/ajouterEchographie.html.twig', [
            'slug' => $slug,
            'licence' => 1,
            'formEchographie' => $form->createView(),
            'dossier' => $this->translator->trans("Echographie"),
            'route' => $this->translator->trans("Ajout d'une échographie"),
        ]);
    }
}

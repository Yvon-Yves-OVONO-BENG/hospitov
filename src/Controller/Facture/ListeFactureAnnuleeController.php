<?php

namespace App\Controller\Facture;

use App\Repository\UserRepository;
use App\Repository\FactureRepository;
use App\Repository\EtatFactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
#[Route('/facture')]
class ListeFactureAnnuleeController extends AbstractController
{
    public function __construct(
        private FactureRepository $factureRepository, 
        private TranslatorInterface $translator,
        private EtatFactureRepository $etatFactureRepository)
    {}

    #[Route('/liste-facture-annulee', name: 'liste_facture_annulee')]
    public function listeFacture(Request $request): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }
        
        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('suppression', null);
        
        // Sinon j'affiche toutes les factures
        $factures = $this->factureRepository->findBy([
                'annulee' => 1
            ], ['dateFactureAt' => 'DESC']);
        
        $etatFactures = $this->etatFactureRepository->findAll();

        return $this->render('facture/listeFacture.html.twig', [
            'licence' => 1,
            'factures' => $factures,
            'etatFactures' => $etatFactures,
            'factureAnnulee' => 1,
            'dossier' => $this->translator->trans("Facture"),
            'route' => $this->translator->trans("Les factures annulées")
        ]);
    }
}
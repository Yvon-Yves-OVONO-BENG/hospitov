<?php

namespace App\Controller;

use DateTime;
use App\Entity\ConstantsClass;
use App\Repository\BilletDeSessionRepository;
use App\Repository\CommandeRepository;
use App\Repository\ConsultationRepository;
use App\Repository\FactureRepository;
use App\Repository\HospitalisationRepository;
use App\Repository\LicenceRepository;
use App\Repository\LigneDeFactureRepository;
use App\Repository\LotRepository;
use App\Repository\ProduitRepository;
use App\Repository\ResultatExamenRepository;
use App\Repository\UserRepository;
use App\Service\PanierService;
use App\Service\StatsProduitsCaissiereService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER and !user.etat", message="Accès refusé. Espace reservé uniquement aux abonnés")
 *
 */
class AccueilController extends AbstractController
{
    public function __construct(
        private RouterInterface $router,
        private EntityManagerInterface $em,
        private PanierService $panierService,
        private LotRepository $lotRepository,
        private TranslatorInterface $translator,
        private UserRepository $userRepository,
        private LicenceRepository $licenceRepository,
        private ProduitRepository $produitRepository,
        private FactureRepository $factureRepository,
        private CommandeRepository $commandeRepository,
        private ConsultationRepository $consultationRepository,
        private ResultatExamenRepository $resultatExamenRepository,
        private LigneDeFactureRepository $ligneDeFactureRepository,
        private BilletDeSessionRepository $billetDeSessionRepository,
        private HospitalisationRepository $hospitalisationRepository,
        private StatsProduitsCaissiereService $statsProduitsCaissiereService,
    )
    {}

    #[Route('/accueil', name: 'accueil')]
    public function accueil(Request $request): Response
    {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        # je récupère ma session
        $maSession = $request->getSession();

        if (!$maSession) 
        {
            $this->redirectToRoute('app_logout');
        }

        $nombreProduits = count($this->panierService->getDetailsPanierProduits($request));
        $netAPayer = $this->panierService->getTotal();

        $maSession->set('nombreProduits', $nombreProduits);
        $maSession->set('netAPayer', number_format($netAPayer, 0, '', ' '));

        #mes variables témoin pour afficher les sweetAlert
        $maSession->set('ajout', null);
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null);
        
        /**
         *@var User
         */
        $user = $this->getUser();
        
        #si l'utilisateur n'est pas connecté déconnecte le
        if (!$user) 
        {   
            return $this->redirectToRoute('app_logout');
        }

        #date du jour
        $aujourdhui = new DateTime('now');

        #je formate 'maintenant' pour calculer le nombre de jours restants de la licence
        $maintenant1 = date_format($aujourdhui, 'Y-m-d');
        $maintenant = new DateTime($maintenant1);

        #je récupère la date d'expiration et je la formate
        $dateExpiration1 = date_format($this->licenceRepository->findAll()[0]->getDateExpirationAt(), 'Y-m-d');
        $dateExpiration = new DateTime($dateExpiration1);

        #je calcul la différence
        $dateDiffExpiration = $maintenant->diff($dateExpiration);
        
        #je récupère tous les utilisateurs
        $utilisateurs = $this->userRepository->findAll();
        $nombreJoursRestant = (int)$dateDiffExpiration->format('%R%a');

        #si le nombre de jour restant est supérieur à zéro(0)
        if ((int)$dateDiffExpiration->format('%R%a') >= 0 ) 
        {
            $licence = $this->licenceRepository->findAll()[0];

            $licence->setNombreJours($nombreJoursRestant);
            $this->em->persist($licence);
            $this->em->flush();

            #POUR LA CAISSIERE ACCUEIL
            if (in_array(ConstantsClass::ROLE_CAISSIERE_ACCUEIL, $user->getRoles()))
            {
                #Les factures du jour
                $facturesDuJour = $this->factureRepository->findBy([
                    'dateFactureAt' => $aujourdhui,
                    'annulee' => 0,
                    'caissiere' => $this->userRepository->find($user->getId())
                ], ['dateFactureAt' => 'DESC']);

                /**
                 * @var User
                 */
                $user = $this->getUser();

                #stats du jour
                $stats = $this->statsProduitsCaissiereService->statProduitsCaissiere($this->userRepository->find($user->getId()));
                // dd($stats);
                $factures = $this->factureRepository->findBy([
                    'annulee' => 0,
                    'caissiere' => $user
                ], ['id' => 'DESC']);

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'stats' => $stats,
                    'route' => 'accueil',
                    'factureAnnulee' => 0,
                    'factures' => $facturesDuJour, 
                    'nombreDeJoursRestants' => $nombreJoursRestant, 

                ]);
            }

            #POUR LA CAISSIERE PHARMACIE
            if(in_array(ConstantsClass::ROLE_CAISSIERE_PHARMACIE, $user->getRoles()))
            {
                #ses factures du jours
                $facturesDuJour = $this->factureRepository->findBy([
                    'dateFactureAt' => $aujourdhui,
                    'annulee' => 0,
                    'caissiere' => $this->userRepository->find($user->getId())
                ], ['dateFactureAt' => 'DESC']);

                $factures = $this->factureRepository->findBy([
                    'annulee' => 0,
                    'caissiere' => $user
                ], ['id' => 'DESC']);

                #je recupère tous les produits
                $tousLesProduits = $this->produitRepository->findBy([
                    'kit' => 0,
                    'supprime' => 0,
                ]);

                #je récupère les produits dont la date de premption est non nulle
                $produits = $this->produitRepository->produits();

                #je calcul les produits périmés dans moins de 90 jours
                $produitsBientotPerimes = [];

                foreach ($produits as $produit) 
                {
                    $aujourdhui = date_format(new DateTime('now'), 'Y-m-d');
                    $aujourdhui = new DateTime($aujourdhui);

                    $datePeremption = date_format($produit->getLot()->getDatePeremptionAt(), ('Y-m-d'));
                    $datePeremption = new DateTime($datePeremption);

                    $dateDiff = $aujourdhui->diff($datePeremption);
                    
                    if ((int)$dateDiff->format('%R%a') <= 90 && ((int)$dateDiff->format('%R%a') > 0) && ($produit->isSupprime() == 0)) 
                    {
                        $produitsBientotPerimes[] = $produit;
                    }
                
                }

                #je calcul les produits périmés
                $produitsPerimes = [];

                foreach ($produits as $produit) 
                {
                    #je récupère le nombre de jour entre la date du jour et la date de peremption du produit
                    $aujourdhui = date_format(new DateTime('now'), 'Y-m-d');
                    $aujourdhui = new DateTime($aujourdhui);

                    $datePeremption = date_format($produit->getLot()->getDatePeremptionAt(), ('Y-m-d'));
                    $datePeremption = new DateTime($datePeremption);

                    $dateDiff = $aujourdhui->diff($datePeremption);
                    
                    if ((int)$dateDiff->format('%R%a') <= 0 && $produit->isSupprime() == 0) 
                    {
                        $produitsPerimes[] = $produit;
                    }
                
                }

                #les commandes
                #je recupère toutes les commandes pour compter
                // $commandes = $this->commandeRepository->findBy([], ['id' => 'DESC' ]);
                $commandes = $this->commandeRepository->findAll();

                ###############################
                #mes variables
                $nombreCash = 0;
                $montantCash = 0;

                $nombrePrisEnCharge = 0;
                $montantPrisEnCharge = 0;

                $nombreCredit = 0;
                $montantCredit = 0;
                foreach ($factures as $facture) 
                {
                    switch ($facture->getModePaiement()->getModePaiement()) {
                        case 'CASH':
                            $nombreCash = $nombreCash + 1;
                            $montantCash += $facture->getAvance();
                            break;

                        case 'PRIS EN CHARGE':
                            $nombrePrisEnCharge = $nombrePrisEnCharge + 1;
                            $montantPrisEnCharge += $facture->getAvance();
                            break;

                        case 'CRÉDIT':
                            $nombreCredit = $nombreCredit + 1;
                            $montantCredit += $facture->getAvance();
                            break;
                        
                    }
                }

                ###################
                #mes variables
                $nombreCashDuJour = 0;
                $montantCashDuJour = 0;

                $nombrePrisEnChargeDuJour = 0;
                $montantPrisEnChargeDuJour = 0;

                $nombreCreditDuJour = 0;
                $montantCreditDuJour = 0;

                foreach ($facturesDuJour as $factureDuJour) 
                {
                    switch ($factureDuJour->getModePaiement()->getModePaiement()) {
                        case ConstantsClass::CASH:
                            $nombreCashDuJour = $nombreCashDuJour + 1;
                            $montantCashDuJour += $factureDuJour->getAvance();
                            break;

                        case ConstantsClass::PRIS_EN_CHARGE:
                            $nombrePrisEnChargeDuJour = $nombrePrisEnChargeDuJour + 1;
                            $montantPrisEnChargeDuJour += $factureDuJour->getAvance();
                            break;

                        case ConstantsClass::CREDIT:
                            $nombreCreditDuJour = $nombreCreditDuJour + 1;
                            $montantCreditDuJour += $factureDuJour->getAvance();
                            break;
                        
                    }
                }

                #je recupère les lots
                $lots = $this->lotRepository->findAll();

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'route' => 'accueil',
                    'nombreDeJoursRestants' => $nombreJoursRestant, 
                    'aujourdhui' => $aujourdhui,
                    'factures' => compact("factures"),
                    'factureAnnulee' => 0,
                    'nombreDeJoursRestants' => $nombreJoursRestant, 
                    'tousLesProduits' => $tousLesProduits,
                    'commandes' => $commandes,
                    // 'facturesDuJourCaissiere' => $facturesDuJourCaissiere,
    
                    'nombreCash' => $nombreCash,
                    'montantCash' => $montantCash,
                    'nombrePrisEnCharge' => $nombrePrisEnCharge,
                    'montantPrisEnCharge' => $montantPrisEnCharge,
                    'nombreCredit' => $nombreCredit,
                    'montantCredit' => $montantCredit,
    
                    'nombreCashDuJour' => $nombreCashDuJour,
                    'montantCashDuJour' => $montantCashDuJour,
                    'nombrePrisEnChargeDuJour' => $nombrePrisEnChargeDuJour,
                    'montantPrisEnChargeDuJour' => $montantPrisEnChargeDuJour,
                    'nombreCreditDuJour' => $nombreCreditDuJour,
                    'montantCreditDuJour' => $montantCreditDuJour,
    
                    'aujourdhui' => $aujourdhui,
                    'factures' => $factures,
                    
                    'produitsPerimes' => $produitsPerimes,
                    'produitsBientotPerimes' => $produitsBientotPerimes,
    
                    ##########################
                    'lots' => $lots,
                    'produits' => $produits,
                    'factureAnnulee' => 0,
    
                ]);
            }

            #POUR LES PARAMETRES VITAUX
            if (in_array(ConstantsClass::ROLE_PARAMETRES_VITAUX, $user->getRoles()))
            {
                #Les session du jour en attente
                $sessionsDuJourEnAttente = $this->billetDeSessionRepository->getBilletsDeSessionDuJourEnAttente();

                #Les session du jour en reçues
                $sessionsDuJourEnRecues = $this->billetDeSessionRepository->getBilletsDeSessionDuJourRecue();

                #Les session du jour
                $sessionsDuJour = $this->billetDeSessionRepository->getBilletsDeSessionDuJour();

                #Toutes les sessions
                $toutesLesSessions = $this->billetDeSessionRepository->getBilletsDeSession();

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'route' => 'Accueil',
                    'factureAnnulee' => 0,
                    'sessions' => $sessionsDuJour, 
                    'toutesLesSessions' => $toutesLesSessions, 
                    'nombreDeJoursRestants' => $nombreJoursRestant, 
                    'sessionsDuJourEnRecues' => $sessionsDuJourEnRecues, 
                    'sessionsDuJourEnAttente' => $sessionsDuJourEnAttente, 

                ]);
            }

            #POUR LES MEDECINS
            if(in_array(ConstantsClass::ROLE_MEDECIN, $this->getUser()->getRoles()))
            {
                #Les consultations du jour en attente
                $consultationsDuJourEnAttente = $this->consultationRepository->getConsultationsDuJourEnAttente($this->getUser());

                #Les consultations du jour en reçues
                $consultationsDuJourEffectuees = $this->consultationRepository->getConsultationsDuJourRecue($this->getUser());

                #Les consultations du jour
                $consultationsDuJour = $this->consultationRepository->getConsultations($this->getUser());

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'route' => 'accueil',
                    'consultations' => $consultationsDuJour,
                    'consultationsDuJourEffectuees' => $consultationsDuJourEffectuees,
                    'consultationsDuJourEnAttente' => $consultationsDuJourEnAttente,
                ]);
            }

            #POUR LES LABORANTINS
            if(in_array(ConstantsClass::ROLE_LABORANTIN, $this->getUser()->getRoles()))
            {
                #Les examens du jour en attente
                $examensDuJourEnAttente = $this->resultatExamenRepository->getExamensDuJourEnAttente();

                #Les examens du jour en reçues
                $examensDuJourEffectuees = $this->resultatExamenRepository->getExamensDuJourEffectues($this->getUser());

                #Les examens du jour
                $examensDuJour = $this->resultatExamenRepository->findBy([
                    'datePrescriptionAt' => (new Datetime('today'))
                ]);
                // dd($examensDuJour);

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'route' => 'accueil',
                    'examens' => $examensDuJour,
                    'examensDuJourEnAttente' => $examensDuJourEnAttente,
                    'examensDuJourEffectuees' => $examensDuJourEffectuees,
                ]);
            }


            #POUR HOSPITALISATION
            if(in_array(ConstantsClass::ROLE_HOSPITALISATION, $this->getUser()->getRoles()))
            {
                #Les hospitalisations du jour 
                $hospitalisationsDuJour = $this->hospitalisationRepository->findAll();

                #Les hospitalisations sorties
                $hospitalisationsSorties = $this->hospitalisationRepository->findAll();

                #toutes les hospitalisations
                $toutesLesHospitalisations = $this->hospitalisationRepository->findAll();
                // dd($examensDuJour);

                return $this->render('accueil/index.html.twig', [
                    'licence' => 1,
                    'route' => 'accueil',
                    'hospitalisations' => $toutesLesHospitalisations,
                    'hospitalisationsDuJour' => $hospitalisationsDuJour,
                    'hospitalisationsSorties' => $hospitalisationsSorties,
                ]);
            }

            if (in_array(ConstantsClass::ROLE_REGIES_DES_RECETTES, $user->getRoles()) || 
            in_array(ConstantsClass::ROLE_ADMINISTRATEUR, $user->getRoles()) || 
            in_array(ConstantsClass::ROLE_SECRETAIRE, $user->getRoles()) || 
            in_array(ConstantsClass::ROLE_PHARMACIEN, $user->getRoles())
            ) 
            {
                //2. Toutes Ses factures
                if (in_array(ConstantsClass::ROLE_REGIES_DES_RECETTES, $user->getRoles()) || 
                in_array(ConstantsClass::ROLE_ADMINISTRATEUR, $user->getRoles()) || 
                in_array(ConstantsClass::ROLE_SECRETAIRE, $user->getRoles()) || 
                in_array(ConstantsClass::ROLE_PHARMACIEN, $user->getRoles()))
                {
                    #ses factures du jours
                    $facturesDuJour = $this->factureRepository->findBy([
                        'dateFactureAt' => $aujourdhui,
                        'annulee' => 0,
                    ], ['dateFactureAt' => 'DESC']);
                    
                    $factures = $this->factureRepository->findBy([
                        'annulee' => 0,
                    ], ['id' => 'DESC']);
                } 
                
                
                #je recupère tous les produits
                $tousLesProduits = $this->produitRepository->findBy([
                    'kit' => 0,
                    'supprime' => 0,
                ]);

                #je récupère les produits dont la date de premption est non nulle
                $produits = $this->produitRepository->produits();

                #je calcul les produits périmés dans moins de 90 jours
                $produitsBientotPerimes = [];

                foreach ($produits as $produit) 
                {
                    $aujourdhui = date_format(new DateTime('now'), 'Y-m-d');
                    $aujourdhui = new DateTime($aujourdhui);

                    $datePeremption = date_format($produit->getLot()->getDatePeremptionAt(), ('Y-m-d'));
                    $datePeremption = new DateTime($datePeremption);

                    $dateDiff = $aujourdhui->diff($datePeremption);
                    
                    if ((int)$dateDiff->format('%R%a') <= 90 && ((int)$dateDiff->format('%R%a') > 0) && ($produit->isSupprime() == 0)) 
                    {
                        $produitsBientotPerimes[] = $produit;
                    }
                
                }

                #je calcul les produits périmés
                $produitsPerimes = [];

                foreach ($produits as $produit) 
                {
                    #je récupère le nombre de jour entre la date du jour et la date de peremption du produit
                    $aujourdhui = date_format(new DateTime('now'), 'Y-m-d');
                    $aujourdhui = new DateTime($aujourdhui);

                    $datePeremption = date_format($produit->getLot()->getDatePeremptionAt(), ('Y-m-d'));
                    $datePeremption = new DateTime($datePeremption);

                    $dateDiff = $aujourdhui->diff($datePeremption);
                    
                    if ((int)$dateDiff->format('%R%a') <= 0 && $produit->isSupprime() == 0) 
                    {
                        $produitsPerimes[] = $produit;
                    }
                
                }

                #les commandes
                #je recupère toutes les commandes pour compter
                $commandes = $this->commandeRepository->findBy([], ['id' => 'DESC' ]);

                ###############################
                #mes variables
                $nombreCash = 0;
                $montantCash = 0;

                $nombrePrisEnCharge = 0;
                $montantPrisEnCharge = 0;

                $nombreCredit = 0;
                $montantCredit = 0;

                foreach ($factures as $facture) 
                {
                    switch ($facture->getModePaiement()->getModePaiement()) {
                        case 'CASH':
                            $nombreCash = $nombreCash + 1;
                            $montantCash += $facture->getAvance();
                            break;

                        case 'PRIS EN CHARGE':
                            $nombrePrisEnCharge = $nombrePrisEnCharge + 1;
                            $montantPrisEnCharge += $facture->getAvance();
                            break;

                        case 'CRÉDIT':
                            $nombreCredit = $nombreCredit + 1;
                            $montantCredit += $facture->getAvance();
                            break;
                        
                    }
                }

                ###################
                #mes variables
                $nombreCashDuJour = 0;
                $montantCashDuJour = 0;

                $nombrePrisEnChargeDuJour = 0;
                $montantPrisEnChargeDuJour = 0;

                $nombreCreditDuJour = 0;
                $montantCreditDuJour = 0;

                foreach ($facturesDuJour as $factureDuJour) 
                {
                    switch ($factureDuJour->getModePaiement()->getModePaiement()) {
                        case ConstantsClass::CASH:
                            $nombreCashDuJour = $nombreCashDuJour + 1;
                            $montantCashDuJour += $factureDuJour->getAvance();
                            break;

                        case ConstantsClass::PRIS_EN_CHARGE:
                            $nombrePrisEnChargeDuJour = $nombrePrisEnChargeDuJour + 1;
                            $montantPrisEnChargeDuJour += $factureDuJour->getAvance();
                            break;

                        case ConstantsClass::CREDIT:
                            $nombreCreditDuJour = $nombreCreditDuJour + 1;
                            $montantCreditDuJour += $factureDuJour->getAvance();
                            break;
                        
                    }
                }

                #je recupère les lots
                $lots = $this->lotRepository->findAll();

                if (in_array(ConstantsClass::ROLE_REGIES_DES_RECETTES, $user->getRoles()) || 
                in_array(ConstantsClass::ROLE_ADMINISTRATEUR, $user->getRoles()) || 
                in_array(ConstantsClass::ROLE_SECRETAIRE, $user->getRoles()) ||
                in_array(ConstantsClass::ROLE_PHARMACIEN, $user->getRoles())) 
                {
                    #ses factures du jours de la caisiere
                    $facturesDuJourCaissiere = $this->factureRepository->findBy([
                        'dateFactureAt' => $aujourdhui,
                        'annulee' => 0
                    ], ['dateFactureAt' => 'DESC']);
                }
                elseif(in_array(ConstantsClass::ROLE_CAISSIERE_ACCUEIL, $user->getRoles()) ||
                in_array(ConstantsClass::ROLE_CAISSIERE_PHARMACIE, $user->getRoles()))
                {
                    #ses factures du jours de la caisiere
                    $facturesDuJourCaissiere = $this->factureRepository->findBy([
                        'caissiere' => $this->userRepository->find($user->getId()),
                        'dateFactureAt' => $aujourdhui,
                        'annulee' => 0
                    ], ['dateFactureAt' => 'DESC']);
                }

                return $this->render('accueil/index.html.twig', [
                    'route' => 'accueil',
                    'licence' => 1,
                    'nombreDeJoursRestants' => $nombreJoursRestant, 
                    'tousLesProduits' => $tousLesProduits,
                    'commandes' => $commandes,
                    'facturesDuJourCaissiere' => $facturesDuJourCaissiere,

                    'nombreCash' => $nombreCash,
                    'montantCash' => $montantCash,
                    'nombrePrisEnCharge' => $nombrePrisEnCharge,
                    'montantPrisEnCharge' => $montantPrisEnCharge,
                    'nombreCredit' => $nombreCredit,
                    'montantCredit' => $montantCredit,

                    'nombreCashDuJour' => $nombreCashDuJour,
                    'montantCashDuJour' => $montantCashDuJour,
                    'nombrePrisEnChargeDuJour' => $nombrePrisEnChargeDuJour,
                    'montantPrisEnChargeDuJour' => $montantPrisEnChargeDuJour,
                    'nombreCreditDuJour' => $nombreCreditDuJour,
                    'montantCreditDuJour' => $montantCreditDuJour,

                    'aujourdhui' => $aujourdhui,
                    'factures' => compact("factures"),
                    
                    'produitsPerimes' => $produitsPerimes,
                    'produitsBientotPerimes' => $produitsBientotPerimes,

                    ##########################
                    'lots' => $lots,
                    'produits' => $produits,
                    'factureAnnulee' => 0,

                ]);
            }   
            
        }
        else
        {
            return $this->render('accueil/index.html.twig', [
                'licence' => 0,
                'route' => 'accueil',
            ]);
        }

        return $this->redirectToRoute('app_login');

    }
}

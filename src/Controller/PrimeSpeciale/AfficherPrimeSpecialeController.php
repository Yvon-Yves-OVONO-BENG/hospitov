<?php

namespace App\Controller\PrimeSpeciale;

use App\Repository\PrimeSpecialeRepository;
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
#[Route('primeSpeciale')]

class AfficherPrimeSpecialeController extends AbstractController
{
    public function __construct(
        private PrimeSpecialeRepository $primeSpecialeRepository,
        private TranslatorInterface $translator
    )
    {}

    #[Route('/afficher-prime-speciale/{a<[0-1]{1}>}/{m<[0-1]{1}>}/{s<[0-1]{1}>}', name: 'afficher_prime_speciale')]
    public function afficherPrimeSpeciale(Request $request, int $a = 0, int $m = 0, int $s = 0): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$maSession)
        {
            return $this->redirectToRoute("app_logout");
        }

        if ($a == 1 || $m == 0 || $s == 0) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la mise à jour
        if ($m == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', 1);
            $maSession->set('suppression', null);
            
        }

        #je teste si le témoin n'est pas vide pour savoir s'il vient de la suppression
        
        if ($s == 1) 
        {
            #mes variables témoin pour afficher les sweetAlert
            $maSession->set('ajout', null);
            $maSession->set('misAjour', null);
            $maSession->set('suppression', 1);
            
        }
        
        #je récupère toutes les primeSpeciales
        $primeSpeciales = $this->primeSpecialeRepository->findAll();
        
        #j'envoie mon tableau des primeSpeciales à mon rendu twig pour affichage
        return $this->render('primeSpeciale/afficherPrimeSpeciale.html.twig', [
            'licence' => 1,
            'primeSpeciales' => $primeSpeciales,
            'route' => $this->translator->trans("Liste des prime spéciales"),
            'dossier' => $this->translator->trans("Primes Spéciales"),
        ]);
    }
}

<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\BulletinGain;
use App\Entity\PrimeSpeciale;
use App\Entity\BulletinRetenue;
use App\Entity\BulletinSalaire;
use App\Repository\BulletinSalaireRepository;
use App\Repository\GainRepository;
use App\Repository\UserRepository;
use App\Repository\RetenueRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class GenererBulletinDePaieService
{
    public function __construct(
        private GainRepository $gainRepo, 
        private EntityManagerInterface $em, 
        private UserRepository $userRepository,
        private RetenueRepository $retenueRepo,
        private BulletinSalaireRepository $bulletinSalaireRepository,
        )
    { }

    public function genererBulletin(User $personnel, int $mois, int $annee, int $caissierId): BulletinSalaire
    {

        $salaireBrute = $personnel->getSalaireBrute();

        // Calcul des gains
        $totalGain = 0;
        $gains = $this->gainRepo->findAll();
        $bulletinGains = [];

        foreach ($gains as $gain) 
        {
            $montant = ($gain->getPourcentage() / 100) * $salaireBrute;
            $bulletinGain = new BulletinGain();
            $bulletinGain->setGain($gain);
            $bulletinGain->setMontant($montant);
            $bulletinGains[] = $bulletinGain;
            $totalGain += $montant;
        }

        // Calcul des retenues
        $totalRetenue = 0;
        $retenues = $this->retenueRepo->findAll();
        $bulletinRetenues = [];

        foreach ($retenues as $retenue) 
        {
            $montant = ($retenue->getPourcentage() / 100) * $salaireBrute;
            $bulletinRetenue = new BulletinRetenue();
            $bulletinRetenue->setRetenue($retenue);
            $bulletinRetenue->setMontant($montant);
            $bulletinRetenues[] = $bulletinRetenue;
            $totalRetenue += $montant;
        }

        $primeSpecialeRepo = $this->em->getRepository(PrimeSpeciale::class);

        $primesSpec = $primeSpecialeRepo->findBy([
            'personnel' => $personnel,
            'mois' => $mois,
            'annee' => $annee
        ]);

        $totalPrimeSpeciale = 0;
        foreach ($primesSpec as $prime) 
        {
            $totalPrimeSpeciale += $prime->getMontant();
        }
        

        // Calcul IRPP
        $montantIrpp = 0.05 * $salaireBrute;

        // Calcul net à payer
        $netAPayer = $salaireBrute + $totalGain - $totalRetenue - $montantIrpp;

        $caissier = $this->userRepository->find($caissierId);

        $dernierBulletin = $this->bulletinSalaireRepository->findOneBy([], ['id' => 'DESC' ]);

        $dernierId = $dernierBulletin ? $dernierBulletin->getId() : 1;

        // Création du bulletin
        $bulletin = new BulletinSalaire();

        $netAPayer = $salaireBrute + $totalGain + $totalPrimeSpeciale - $totalRetenue - $montantIrpp;
        $bulletin; // Si tu ajoutes ce champ dans la table bulletin

        $maintenant = new \DateTime();
        $moisRef = $maintenant->format('m');

        $bulletin->setPersonnel($personnel)
                ->setReference($moisRef.$annee.$dernierId)
                ->setMois($mois)
                ->setAnnee($annee)
                ->setSalaireBrute($salaireBrute)
                ->setTotalGains($totalGain)
                ->setTotalRetenues($totalRetenue)
                ->setMontantIrpp($montantIrpp)
                ->setNetAPayer($netAPayer)
                ->setTotalPrimeSpeciale($totalPrimeSpeciale)
                ->setDatePaiementAt(new \DateTime('now'))
                ->setCaissiere($caissier);

        $this->em->persist($bulletin);

        foreach ($bulletinGains as $gain) 
        {
            $gain->setBulletinSalaire($bulletin);
            $this->em->persist($gain);
        }

        foreach ($bulletinRetenues as $retenue) 
        {
            $retenue->setBulletinSalaire($bulletin);
            $this->em->persist($retenue);
        }

        $this->em->flush();

        return $bulletin;
    }
}
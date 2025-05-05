<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\BulletinSalaireRepository;
use App\Repository\GainRepository;
use App\Repository\LigneDeFactureRepository;
use App\Repository\UserRepository;
use App\Repository\RetenueRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatsProduitsCaissiereService
{
    public function __construct( 
        private UserRepository $userRepository,
        private LigneDeFactureRepository $ligneDeFactureRepository
        )
    { }

    public function statProduitsCaissiere(User $user)
    {
        $statDuJourCaissiere = [];

        #les billets de session du jour
        $statDuJourCaissiere['billets'] = count($this->ligneDeFactureRepository->getBilletsDeSessionDuJour($this->userRepository->find($user->getId())));
        
        #les carnet du jour
        $statDuJourCaissiere['carnets'] = count($this->ligneDeFactureRepository->getCarnetDuJour($this->userRepository->find($user->getId())));
        
        #les echographies de session du jour
        $statDuJourCaissiere['echographies'] = count($this->ligneDeFactureRepository->getEchographiesDuJour($this->userRepository->find($user->getId())));
        
        #les examens de session du jour
        $statDuJourCaissiere['examens'] = count($this->ligneDeFactureRepository->getExamenDuJour($this->userRepository->find($user->getId())));

        #les kits de session du jour
        $statDuJourCaissiere['kits'] = count($this->ligneDeFactureRepository->getKitDuJour($this->userRepository->find($user->getId())));
        
        #les radios de session du jour
        $statDuJourCaissiere['radios'] = count($this->ligneDeFactureRepository->getRadioDuJour($this->userRepository->find($user->getId())));
        
        return $statDuJourCaissiere;
    }
}
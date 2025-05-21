<?php

namespace App\Service;

use App\Entity\Facture;
use App\Entity\HistoriquePaiement;
use App\Repository\HistoriquePaiementRepository;
use Doctrine\ORM\EntityManagerInterface;

class FactureAvanceService
{
    public function __construct(
        private EntityManagerInterface $em,
        private HistoriquePaiementRepository $historiquePaiementRepository
    ) {}

    public function getHistoriqueFamille(Facture $facture): array
    {
        // Monter jusqu'à la facture mère
        while ($facture->getFactureMere() !== null) {
            $facture = $facture->getFactureMere();
        }

        // Récupérer toutes les factures filles récursivement
        $toutesLesFactures = $this->recupererToutesLesSousFactures($facture);
        $toutesLesFactures[] = $facture; // inclure la mère

        // Récupérer tous les paiements liés à ces factures
        return $this->historiquePaiementRepository->createQueryBuilder('h')
            ->where('h.facture IN (:factures)')
            ->setParameter('factures', $toutesLesFactures)
            ->orderBy('h.dateAvanceAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function recupererToutesLesSousFactures(Facture $facture): array
    {
        $result = [];

        foreach ($facture->getSousPaiements() as $sousFacture) {
            $result[] = $sousFacture;
            $result = array_merge($result, $this->recupererToutesLesSousFactures($sousFacture));
        }

        return $result;
    }
}
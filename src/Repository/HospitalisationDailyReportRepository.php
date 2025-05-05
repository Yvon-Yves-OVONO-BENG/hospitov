<?php

namespace App\Repository;

use App\Entity\HospitalisationDailyReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HospitalisationDailyReport>
 *
 * @method HospitalisationDailyReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method HospitalisationDailyReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method HospitalisationDailyReport[]    findAll()
 * @method HospitalisationDailyReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HospitalisationDailyReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HospitalisationDailyReport::class);
    }

    //    /**
    //     * @return HospitalisationDailyReport[] Returns an array of HospitalisationDailyReport objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?HospitalisationDailyReport
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

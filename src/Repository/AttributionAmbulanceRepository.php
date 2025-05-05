<?php

namespace App\Repository;

use App\Entity\AttributionAmbulance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AttributionAmbulance>
 *
 * @method AttributionAmbulance|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttributionAmbulance|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttributionAmbulance[]    findAll()
 * @method AttributionAmbulance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttributionAmbulanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttributionAmbulance::class);
    }

    //    /**
    //     * @return AttributionAmbulance[] Returns an array of AttributionAmbulance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AttributionAmbulance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

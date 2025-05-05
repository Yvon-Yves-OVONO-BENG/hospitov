<?php

namespace App\Repository;

use App\Entity\Ambulance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ambulance>
 *
 * @method Ambulance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ambulance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ambulance[]    findAll()
 * @method Ambulance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmbulanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ambulance::class);
    }

    //    /**
    //     * @return Ambulance[] Returns an array of Ambulance objects
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

    //    public function findOneBySomeField($value): ?Ambulance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

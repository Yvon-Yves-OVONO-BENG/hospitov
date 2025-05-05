<?php

namespace App\Repository;

use App\Entity\BulletinGain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BulletinGain>
 *
 * @method BulletinGain|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulletinGain|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulletinGain[]    findAll()
 * @method BulletinGain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulletinGainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BulletinGain::class);
    }

    //    /**
    //     * @return BulletinGain[] Returns an array of BulletinGain objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BulletinGain
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

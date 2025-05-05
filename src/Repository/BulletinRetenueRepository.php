<?php

namespace App\Repository;

use App\Entity\BulletinRetenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BulletinRetenue>
 *
 * @method BulletinRetenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulletinRetenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulletinRetenue[]    findAll()
 * @method BulletinRetenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulletinRetenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BulletinRetenue::class);
    }

    //    /**
    //     * @return BulletinRetenue[] Returns an array of BulletinRetenue objects
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

    //    public function findOneBySomeField($value): ?BulletinRetenue
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

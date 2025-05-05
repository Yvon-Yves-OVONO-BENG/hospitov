<?php

namespace App\Repository;

use App\Entity\Gain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gain>
 *
 * @method Gain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gain[]    findAll()
 * @method Gain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gain::class);
    }

    //    /**
    //     * @return Gain[] Returns an array of Gain objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Gain
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

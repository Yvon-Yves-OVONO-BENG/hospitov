<?php

namespace App\Repository;

use App\Entity\Retenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Retenue>
 *
 * @method Retenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Retenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Retenue[]    findAll()
 * @method Retenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retenue::class);
    }

    //    /**
    //     * @return Retenue[] Returns an array of Retenue objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Retenue
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

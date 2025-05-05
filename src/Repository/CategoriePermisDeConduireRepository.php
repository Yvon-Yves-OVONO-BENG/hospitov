<?php

namespace App\Repository;

use App\Entity\CategoriePermisDeConduire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriePermisDeConduire>
 *
 * @method CategoriePermisDeConduire|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriePermisDeConduire|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriePermisDeConduire[]    findAll()
 * @method CategoriePermisDeConduire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriePermisDeConduireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriePermisDeConduire::class);
    }

    //    /**
    //     * @return CategoriePermisDeConduire[] Returns an array of CategoriePermisDeConduire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CategoriePermisDeConduire
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

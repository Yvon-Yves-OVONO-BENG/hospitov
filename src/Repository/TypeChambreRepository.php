<?php

namespace App\Repository;

use App\Entity\TypeChambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeChambre>
 *
 * @method TypeChambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeChambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeChambre[]    findAll()
 * @method TypeChambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeChambre::class);
    }

    //    /**
    //     * @return TypeChambre[] Returns an array of TypeChambre objects
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

    //    public function findOneBySomeField($value): ?TypeChambre
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

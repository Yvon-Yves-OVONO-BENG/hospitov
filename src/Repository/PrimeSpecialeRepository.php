<?php

namespace App\Repository;

use App\Entity\PrimeSpeciale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrimeSpeciale>
 *
 * @method PrimeSpeciale|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrimeSpeciale|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrimeSpeciale[]    findAll()
 * @method PrimeSpeciale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrimeSpecialeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrimeSpeciale::class);
    }

    //    /**
    //     * @return PrimeSpeciale[] Returns an array of PrimeSpeciale objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PrimeSpeciale
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

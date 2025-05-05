<?php

namespace App\Repository;

use App\Entity\PrescriptionExamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrescriptionExamen>
 *
 * @method PrescriptionExamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrescriptionExamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrescriptionExamen[]    findAll()
 * @method PrescriptionExamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrescriptionExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrescriptionExamen::class);
    }

    //    /**
    //     * @return PrescriptionExamen[] Returns an array of PrescriptionExamen objects
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

    //    public function findOneBySomeField($value): ?PrescriptionExamen
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\PrescriptionMedicament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrescriptionMedicament>
 *
 * @method PrescriptionMedicament|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrescriptionMedicament|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrescriptionMedicament[]    findAll()
 * @method PrescriptionMedicament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrescriptionMedicamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrescriptionMedicament::class);
    }

    //    /**
    //     * @return PrescriptionMedicament[] Returns an array of PrescriptionMedicament objects
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

    //    public function findOneBySomeField($value): ?PrescriptionMedicament
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\LigneConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneConsultation>
 *
 * @method LigneConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneConsultation[]    findAll()
 * @method LigneConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneConsultation::class);
    }

    //    /**
    //     * @return LigneConsultation[] Returns an array of LigneConsultation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LigneConsultation
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\StatutHospitalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatutHospitalisation>
 *
 * @method StatutHospitalisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutHospitalisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutHospitalisation[]    findAll()
 * @method StatutHospitalisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutHospitalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutHospitalisation::class);
    }

    //    /**
    //     * @return StatutHospitalisation[] Returns an array of StatutHospitalisation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?StatutHospitalisation
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

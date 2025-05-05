<?php

namespace App\Repository;

use App\Entity\StatutPersonnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatutPersonnel>
 *
 * @method StatutPersonnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutPersonnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutPersonnel[]    findAll()
 * @method StatutPersonnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutPersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutPersonnel::class);
    }

    //    /**
    //     * @return StatutPersonnel[] Returns an array of StatutPersonnel objects
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

    //    public function findOneBySomeField($value): ?StatutPersonnel
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

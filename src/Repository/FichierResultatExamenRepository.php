<?php

namespace App\Repository;

use App\Entity\FichierResultatExamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichierResultatExamen>
 *
 * @method FichierResultatExamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichierResultatExamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichierResultatExamen[]    findAll()
 * @method FichierResultatExamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichierResultatExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichierResultatExamen::class);
    }

    //    /**
    //     * @return FichierResultatExamen[] Returns an array of FichierResultatExamen objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FichierResultatExamen
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

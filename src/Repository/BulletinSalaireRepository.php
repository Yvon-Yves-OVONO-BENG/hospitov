<?php

namespace App\Repository;

use App\Entity\BulletinSalaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BulletinSalaire>
 *
 * @method BulletinSalaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulletinSalaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulletinSalaire[]    findAll()
 * @method BulletinSalaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulletinSalaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BulletinSalaire::class);
    }

//    /**
//     * @return BulletinSalaire[] Returns an array of BulletinSalaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BulletinSalaire
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

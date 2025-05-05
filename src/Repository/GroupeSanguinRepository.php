<?php

namespace App\Repository;

use App\Entity\GroupeSanguin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupeSanguin>
 *
 * @method GroupeSanguin|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeSanguin|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeSanguin[]    findAll()
 * @method GroupeSanguin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeSanguinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeSanguin::class);
    }

    //    /**
    //     * @return GroupeSanguin[] Returns an array of GroupeSanguin objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GroupeSanguin
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

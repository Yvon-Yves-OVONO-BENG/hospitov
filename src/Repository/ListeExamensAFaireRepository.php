<?php

namespace App\Repository;

use App\Entity\ListeExamensAFaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeExamensAFaire>
 *
 * @method ListeExamensAFaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeExamensAFaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeExamensAFaire[]    findAll()
 * @method ListeExamensAFaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeExamensAFaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeExamensAFaire::class);
    }

    //    /**
    //     * @return ListeExamensAFaire[] Returns an array of ListeExamensAFaire objects
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

    //    public function findOneBySomeField($value): ?ListeExamensAFaire
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

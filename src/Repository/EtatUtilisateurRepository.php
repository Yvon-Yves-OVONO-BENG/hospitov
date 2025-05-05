<?php

namespace App\Repository;

use App\Entity\EtatUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EtatUtilisateur>
 *
 * @method EtatUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatUtilisateur[]    findAll()
 * @method EtatUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatUtilisateur::class);
    }

    //    /**
    //     * @return EtatUtilisateur[] Returns an array of EtatUtilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EtatUtilisateur
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

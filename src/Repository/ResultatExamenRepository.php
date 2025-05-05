<?php

namespace App\Repository;

use App\Entity\Consultation;
use App\Entity\ParametresVitaux;
use DateTime;
use App\Entity\ResultatExamen;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<ResultatExamen>
 *
 * @method ResultatExamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultatExamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultatExamen[]    findAll()
 * @method ResultatExamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultatExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, ResultatExamen::class);
    }


    /**
     * fonction qui retourne les examens en attente
     *
     * @return void
     */
    public function getExamensDuJourEnAttente()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('r')
        ->from(ResultatExamen::class, 'r')
        ->where('r.realise = 0')
        ;

        return $qb->getQuery()->getResult();

    }

    /**
     * fonction qui retourne les examens effectués
     *
     * @return void
     */
    public function getExamensDuJourEffectues()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('r')
        ->from(ResultatExamen::class, 'r')
        ->where('r.realise = 1')
        ;

        return $qb->getQuery()->getResult();

    }


    //    /**
    //     * @return ResultatExamen[] Returns an array of ResultatExamen objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ResultatExamen
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

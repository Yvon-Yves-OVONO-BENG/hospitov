<?php

namespace App\Repository;

use DateTime;
use App\Entity\ParametresVitaux;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<ParametresVitaux>
 *
 * @method ParametresVitaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametresVitaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametresVitaux[]    findAll()
 * @method ParametresVitaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametresVitauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, ParametresVitaux::class);
    }


    /**
     * fonction qui retourne les paramètres du jour
     *
     * @return void
     */
    public function getParametresVitauxDuJour(int $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('p')
        ->from(ParametresVitaux::class, 'p')
        ->where('p.infirmier = :user')
        ->andWhere('p.datePriseAt = :datePriseAt')
        ->setParameter('user', $user)
        ->setParameter('datePriseAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('p.datePriseAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }


    /**
     * fonction qui retourne tous les paramètres
     *
     * @return void
     */
    public function getTousLesParametresVitaux(User $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('p')
        ->from(ParametresVitaux::class, 'p')
        ->where('p.infirmier = :user')
        ->setParameter('user', $user)
        ->orderBy('p.datePriseAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }


    public function getHistoriqueParametreVitaux(int $patientId)
    {
        $qb = $this->createQueryBuilder('pv');

        $qb->select('pv')
            ->join('pv.billetDeSession', 'bs')
            ->join('bs.patient', 'p')
            ->where('p.id = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('pv.datePriseAt', 'DESC');

            return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return ParametresVitaux[] Returns an array of ParametresVitaux objects
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

    //    public function findOneBySomeField($value): ?ParametresVitaux
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

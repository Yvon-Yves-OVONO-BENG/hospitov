<?php

namespace App\Repository;

use DateTime;
use App\Entity\BilletDeSession;
use App\Entity\Patient;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<BilletDeSession>
 *
 * @method BilletDeSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilletDeSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilletDeSession[]    findAll()
 * @method BilletDeSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilletDeSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, BilletDeSession::class);
    }

    /**
     * fonction qui retourne les session en attente
     *
     * @return void
     */
    public function getBilletsDeSessionDuJourEnAttente()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('b')
        ->from(BilletDeSession::class, 'b')
        ->where('b.etat = :etat')
        ->andWhere('b.dateBilletDeSessionAt = :dateBilletDeSessionAt')
        ->setParameter('etat', 0)
        ->setParameter('dateBilletDeSessionAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('b.dateBilletDeSessionAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }

    /**
     * fonction qui retourne les session en reçues
     *
     * @return void
     */
    public function getBilletsDeSessionDuJourRecue()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('b')
        ->from(BilletDeSession::class, 'b')
        ->where('b.etat = :etat')
        ->andWhere('b.dateBilletDeSessionAt = :dateBilletDeSessionAt')
        ->setParameter('etat', 1)
        ->setParameter('dateBilletDeSessionAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('b.dateBilletDeSessionAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }


    /**
     * fonction qui retourne toutes les sessions
     *
     * @return void
     */
    public function getBilletsDeSession()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('b')
        ->from(BilletDeSession::class, 'b')
        ->where('b.dateBilletDeSessionAt = :dateBilletDeSessionAt')
        ->setParameter('dateBilletDeSessionAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('b.dateBilletDeSessionAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }


    /**
     * fonction qui retourne toutes les sessions du jour
     *
     * @return void
     */
    public function getBilletsDeSessionDuJour()
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('b')
        ->from(BilletDeSession::class, 'b')
        ->where('b.dateBilletDeSessionAt = :dateBilletDeSessionAt')
        ->setParameter('dateBilletDeSessionAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('b.dateBilletDeSessionAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }

    public function findDerniereSessionDuPatient(Patient $patient): ?BilletDeSession
    {
        return $this->createQueryBuilder('b')
        ->andWhere('b.patient = :patient')
        ->setParameter('patient', $patient)
        ->orderBy('b.dateBilletDeSessionAt', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }

    //    /**
    //     * @return BilletDeSession[] Returns an array of BilletDeSession objects
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

    //    public function findOneBySomeField($value): ?BilletDeSession
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

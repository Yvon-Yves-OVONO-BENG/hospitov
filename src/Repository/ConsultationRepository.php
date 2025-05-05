<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\Consultation;
use App\Entity\ParametresVitaux;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Consultation>
 *
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Consultation::class);
    }


    /**
     * fonction qui retourne les consultation en attente
     *
     * @return void
     */
    public function getConsultationsDuJourEnAttente(User $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('c')
        ->from(Consultation::class, 'c')
        ->where('c.medecin = :user')
        ->andWhere('c.dateConsultationAt IS NULL')
        ->setParameter('user', $user)
        ;

        return $qb->getQuery()->getResult();

    }

    /**
     * fonction qui retourne les Consultations en reçues
     *
     * @return void
     */
    public function getConsultationsDuJourRecue(User $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('c')
        ->from(Consultation::class, 'c')
        ->where('c.medecin = :user')
        ->andWhere('c.dateConsultationAt = :dateConsultationAt')
        ->setParameter('user', $user)
        ->setParameter('dateConsultationAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('c.dateConsultationAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }


    /**
     * fonction qui retourne toutes les Consultations
     *
     * @return void
     */
    public function getConsultations(User $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('c')
        ->from(Consultation::class, 'c')
        ->where('c.medecin = :medecin')
        ->setParameter('medecin', $user)
        ->orderBy('c.dateConsultationAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }

    /**
     * fonction qui retourne les consultations du jour
     *
     * @return void
     */
    public function getConsultationsDuJour(int $user)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select('c')
        ->from(Consultation::class, 'c')
        ->innerJoin(ParametresVitaux::class, 'p')
        ->where('c.medecin = :user')
        ->andWhere('p.id = c.parametresVitaux')
        ->andWhere('p.datePriseAt = :datePriseAt')
        ->setParameter('user', $user)
        ->setParameter('datePriseAt', (new DateTime('now'))->format('Y-m-d'))
        ->orderBy('p.datePriseAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();

    }

    //    /**
    //     * @return Consultation[] Returns an array of Consultation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Consultation
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

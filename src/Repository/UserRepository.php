<?php

namespace App\Repository;

use App\Entity\ConstantsClass;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * les chauffeurs
     */
    public function findChauffeurs()
    {
        return $this->createQueryBuilder('u')
        ->join('u.typeUtilisateur', 't')
        ->where('t.typeUtilisateur = :typeUtilisateur')
        ->setParameter('typeUtilisateur', 'CHAUFFEUR')
        // ->getQuery()
        // ->getResult()
        ;
    }


    /**
     * les medecins
     *
     * @return void
     */
    public function medecinsSpecialiste()
    {
        return $this->createQueryBuilder('u')
        ->join('u.typeUtilisateur', 't')
        ->where('t.typeUtilisateur = :typeUtilisateur')
        ->setParameter('typeUtilisateur', 'MEDECIN')
        ->getQuery()
        ->getResult()
        ;
    }


    public function getPrescripteurs()
    {
        return $this->createQueryBuilder('u')
        ->join('u.typeUtilisateur', 't')
        ->where('t.typeUtilisateur = :typeUtilisateur')
        ->setParameter('typeUtilisateur', 'MEDECIN')
        // ->getQuery()
        // ->getResult()
        ;
    }


    /**
     * function qui retourne le personnel en poste
     *
     * @return void
     */
    public function getUserApprobation()
    {
        return $this->createQueryBuilder('u')
        ->join('u.statutPersonnel', 's')
        ->where('s.statutPersonnel = :enPoste')
        ->orWhere('s.statutPersonnel = :plusEnPoste')
        ->orWhere('s.statutPersonnel = :enCoursDeRecrutement')
        ->setParameter('enPoste', ConstantsClass::EN_POSTE)
        ->setParameter('plusEnPoste', ConstantsClass::PLUS_EN_POSTE)
        ->setParameter('enCoursDeRecrutement', ConstantsClass::EN_COURS_DE_RECRUTEMENT)
        ->getQuery()
        ->getResult()
        ;
    }



    public function getUserPrimeSpeciale()
    {
        return $this->createQueryBuilder('u')
        ->join('u.statutPersonnel', 's')
        ->where('s.statutPersonnel = :enPoste')
        ->orWhere('s.statutPersonnel = :enCoursDeRecrutement')
        ->setParameter('enPoste', ConstantsClass::EN_POSTE)
        ->setParameter('enCoursDeRecrutement', ConstantsClass::EN_COURS_DE_RECRUTEMENT)
        ;
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

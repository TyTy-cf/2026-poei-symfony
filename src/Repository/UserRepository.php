<?php

namespace App\Repository;

use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, User::class);
  }

  public function findUser(array $criteria): ?User
  {
    return $this->createQueryBuilder('u')
      ->select('u', 'uog', 'r', 'g', 'c', 'g1')
      ->leftJoin('u.userOwnGames', 'uog')
      ->leftJoin('uog.game', 'g')
      ->leftJoin('u.reviews', 'r')
      ->leftJoin('r.game', 'g1')
      ->join('u.country', 'c')
      ->groupBy('u.id')
      ->setParameter('name', $criteria['name'])
      ->andWhere('u.name = :name')
      ->getQuery()
      ->getOneOrNullResult()
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

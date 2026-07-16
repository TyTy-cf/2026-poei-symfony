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

    public function findFullBy(string $name): ?User
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'uog', 'r', 'c', 'g')
            ->leftJoin('u.userOwnGames', 'uog')
            ->leftJoin('u.reviews', 'r')
            ->leftJoin('u.country', 'c')
            ->leftJoin('uog.game', 'g')
            ->where('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

}

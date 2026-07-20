<?php

namespace App\Repository;

use App\Entity\UserOwnGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserOwnGame>
 */
class UserOwnGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnGame::class);
    }

    public function findLatest(?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('uog')
            ->select('uog, u, g')
            ->leftJoin('uog.game', 'g')
            ->leftJoin('uog.user', 'u')
            ->orderBy('uog.createdAt', 'DESC');

        if ($limit !== null)     {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

}

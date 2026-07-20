<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getQb(): QueryBuilder
    {
        return $this->createQueryBuilder('r');
    }

    /**
     * Return a QueryBuilder fully joined with foreign tables (user & game for review)
     * @return QueryBuilder
     */
    public function getQbFull(): QueryBuilder
    {
        return $this->getQb()
            ->join('r.user', 'u')
            ->join('r.game', 'g');
    }

    public function findFullByRatingMax(?int $limit = null): array
    {
        $qb = $this->getQbFull()
            ->select('r', 'u', 'g')
            ->where('r.rating = 5')
            ->orderBy('r.createdAt', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLatest(?int $limit = null): array
    {
        $qb = $this->getQb()
            ->select('r', 'g', 'u')
            ->leftJoin('r.game', 'g')
            ->leftJoin('r.user', 'u')
            ->orderBy('r.createdAt', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

}

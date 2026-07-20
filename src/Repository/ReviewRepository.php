<?php

namespace App\Repository;

use App\Entity\Game;
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

    //    /**
    //     * @return Review[] Returns an array of Review objects
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

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getQb(): QueryBuilder
    {
        return $this->createQueryBuilder('r');
    }

    public function getQbFull(): QueryBuilder
    {
        return $this->getQb()
            ->join('r.user', 'u')
            ->join('r.game', 'g');
    }

    public function findFullByRatingMax(?int $limit = null)
    {
        $qb = $this->getQbFull()
            ->select('r', 'u', 'g')
            ->where('r.rating = 5')
            ->orderBy('r.createdAt', 'DESC');

        if ($limit !== null)
        {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findFullBy(array $order, ?int $limit = null, ?int $page = null)
    {
        $qb = $this->getQbFull()
            ->select('r', 'u', 'g')
            ->orderBy('r.createdAt', 'DESC');

        if ($limit !== null)
        {
            $qb->setMaxResults($limit);
        }
        if ($page !== null)
        {
            $qb->setFirstResult(($page-1)*$limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getAverageScore(Game $game)
    {
        $qb = $this->getQb()
            ->select('AVG(r.rating) as averageScore')
            ->where('r.game = :gameName')
            ->setParameter('gameName', $game);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByUser(\App\Entity\User|null $user)
    {
        $qb = $this->getQb()
            ->select('r, g')
            ->join('r.user', 'u')
            ->join('r.game', 'g')
            ->where('u = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    public function findLatest(int $int)
    {
        $qb = $this->getQb()
            ->select('r, g, u')
            ->leftJoin('r.game', 'g')
            ->leftJoin('r.user', 'u')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($int);

        return $qb->getQuery()->getResult();
    }
}

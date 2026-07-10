<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Game
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function getQb(): QueryBuilder
    {
        return $this->createQueryBuilder('g');
    }

    public function findByBests(?int $limit = null): array
    {
        $mostPlayed = $this->getQb()
            ->join('g.userOwnGames', 'uog')
            ->groupBy('g.id')
            ->orderBy("sum(uog.gameTime)", 'DESC')
            ->setMaxResults($limit);

        return $mostPlayed->getQuery()->getResult();

    }

    public function findTrends(?int $limit = null): array
    {
        $trends = $this->getQb()
            ->select('g')
            ->orderBy('g.publishedAt', 'DESC')
            ->setMaxResults($limit);

        if ($limit !== null) {
            $trends->setMaxResults($limit);
        }

        return $trends->getQuery()->getResult();
    }

    public function findByTop(?int $limit = null): array
    {
        $mostPlayed = $this->getQb()
            ->select('g', 'r')
            ->join('g.reviews', 'r')
            ->groupBy('g.id')
            ->orderBy("avg(r.rating)", 'DESC')
            ->setMaxResults($limit);

        return $mostPlayed->getQuery()->getResult();

    }

    public function findByUser(?string $name): array
    {
        $mostPlayed = $this->getQb()
            ->join('g.userOwnGames', 'uog')
            ->groupBy('g.id')
            ->orderBy("sum(uog.gameTime)", 'DESC');


        return $mostPlayed->getQuery()->getResult();

    }
}

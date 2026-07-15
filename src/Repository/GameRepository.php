<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function mostPlayedGames(?int $limit){
        $mostPlayedGames = $this->createQueryBuilder('g')
            -> groupBy("g.id")
            -> orderBy("sum(uog.gameTime)", "DESC")
            -> join("g.userOwnGames", "uog")
            -> setMaxResults($limit);

        return $mostPlayedGames->getQuery()->getResult();

    }

    public function bestGames(?int $limit){
        $bestGames = $this->createQueryBuilder("g")
            ->orderBy("g.publishedAt", "DESC")
            ->setMaxResults($limit);

        return $bestGames->getQuery()->getResult();
    }

    public function topRatingGames(?int $limit){
        $topRatingGames = $this->createQueryBuilder("g")
            -> join("g.reviews", "r")
            -> groupBy("g.id")
            -> orderBy("avg(r.rating)", "DESC")
            -> setMaxResults($limit);

        return $topRatingGames->getQuery()->getResult();

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
}

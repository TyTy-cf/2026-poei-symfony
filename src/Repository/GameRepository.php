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



  /**
   * @return Game[] Returns an array of Game objects
   */
  public function findByPopularity(int $value): array
  {
    return $this->createQueryBuilder('g')
      ->leftJoin('g.reviews', 'r')
      ->groupBy('g.id')
      ->orderBy('AVG(r.rating)', 'DESC')
      ->setMaxResults($value)
      ->getQuery()
      ->getResult()

    ;
  }

  /**
   * @return Game[] Returns an array of Game objects
   */
  public function findLatestReleases(int $value): array
  {
    // select latest published games
    return $this->createQueryBuilder('g')
      ->orderBy('g.publishedAt', 'DESC')
      ->setMaxResults($value)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Game[] Returns an array of Game objects
   */
  public function mostPlayedGames(int $value): array
  {

    // select ost played games based on the game_time
    return $this->createQueryBuilder('g')
      ->Join('g.userOwnGames', 'uog')
      ->groupBy('g.id')
      ->orderBy('SUM(uog.gameTime)', 'DESC')
      ->setMaxResults($value)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Game Returns a single Game object or null
   */
  public function findOneFullBy(string $slug): ?Game
  {
    return $this->createQueryBuilder('g')
      ->select('g', 'ca', 'p', 'co')
      ->leftJoin('g.categories', 'ca')
      ->leftJoin('g.publisher', 'p')
      ->leftJoin('g.countries', 'co')
      ->where('g.slug = :slug')
      ->setParameter('slug', $slug)
      ->getQuery()
      ->getOneOrNullResult();
  }




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

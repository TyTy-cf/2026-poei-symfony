<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Category::class);
  }

  /**
   * @return Category[] Returns an array of Category objects
   */
  public function mostPlayedCategories(int $value): array
  {

    // select ost played categories based on the sum of gameTime of all userOwnGames in each category
    return $this->createQueryBuilder('c')
      ->select('c')
      ->leftJoin('c.games', 'g')
      ->leftJoin('g.userOwnGames', 'uog')
      ->groupBy('c.id')
      ->orderBy('SUM(uog.gameTime)', 'DESC')
      ->setMaxResults($value)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Category[] Returns a single Category object or null
   */
  public function FindAllGamesInCategory(array $criteria): ?array
  {

    // select category and all games within the same category in order to access every game with the same category
    return $this->createQueryBuilder('c')
      ->leftJoin('c.games', 'g')
      ->addSelect('g')
      ->where('c.name = :name')
      ->setParameter('name', $criteria['name'])
      ->getQuery()
      ->getResult()
    ;
  }


  /**
   * @return Category[] Returns a single Category object or null
   */
  public function findAllCategories(): ?array
  {
    return $this->createQueryBuilder('c')
      ->leftJoin('c.games', 'g')
      ->addSelect('g')
      ->getQuery()
      ->getResult()
    ;
  }


  //    /**
  //     * @return Category[] Returns an array of Category objects
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

  //    public function findOneBySomeField($value): ?Category
  //    {
  //        return $this->createQueryBuilder('c')
  //            ->andWhere('c.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}

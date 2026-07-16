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

    public function findBestCategories(int $limit = null): array
    {
        $qb = $this->createQueryBuilder("c")
            ->join("c.games", "g")
            ->join("g.userOwnGames", "uog")
            ->orderBy("SUM(uog.gameTime)", "DESC")
            ->groupBy("c")
            ->setMaxResults($limit);

            return $qb->getQuery()->getResult();

    }

}

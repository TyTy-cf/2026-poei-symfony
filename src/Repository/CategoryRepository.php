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
        return $this->findBy([], ['name' => 'ASC'], $limit);
    }

    /**
     * @return Category|null
     */

    public function findAllWithGamesOrdered(string $slug): ?Category
    {
        return $this->createQueryBuilder('c')

            ->leftJoin('c.games', 'g')
            ->addSelect('g')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

}

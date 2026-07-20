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

    public function showAll(): array
    {
        $qb = $this->createQueryBuilder("c")
            ->leftJoin("c.games","g")
            ->select("c", "SUM(g)")
            ->groupBy("c")
            ->orderBy("c.name");
        return $qb->getQuery()->getResult();

    }

    public function showOne(string $slug): ?Category
    {
        $qb = $this->createQueryBuilder("c")
            ->where("c.slug = $slug");
        return $qb->getQuery()->getResult();
    }

}

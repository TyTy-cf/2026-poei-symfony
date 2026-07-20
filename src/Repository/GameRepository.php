<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
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

    public function getQb(): QueryBuilder
    {
        return $this->createQueryBuilder('g');
    }

    public function getQbFull(): QueryBuilder
    {
        return $this->getQb()
            ->leftJoin('g.publisher', 'p')
            ->leftJoin('g.reviews', 'r')
            ->leftJoin('g.categories', 'cat')
            ->leftJoin('g.countries', 'ctr')
            ->leftJoin('g.userOwnGames', 'u');
    }

    public function findTrending()
    {
        $qb = $this->getQb()
            ->leftJoin('g.userOwnGames', 'u')
            ->groupBy('g.slug')
            ->orderBy('SUM(u.gameTime)', 'DESC')
            ->setMaxResults(9);

        return $qb->getQuery()->getResult();
    }

    public function findTop()
    {
        $qb = $this->getQb()
            ->leftJoin('g.reviews', 'r')
            ->groupBy('g.slug')
            ->orderBy('AVG(r.rating)', 'DESC')
            ->setMaxResults(9);

        return $qb->getQuery()->getResult();
    }

    public function findByUser(\App\Entity\User|null $user)
    {
        $qb = $this->getQb()
            ->select('uog, g')
            ->join('g.userOwnGames', 'uog')
            ->where('uog.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Collection<int, Category> $categories
     * @return array
     */
    public function findByCategories(Collection $categories): array
    {
        $qb = $this->getQb()
            ->join('g.categories', 'cat')
            ->andWhere('cat in (:categories)')
            ->setParameter('categories', $categories)
            ->setMaxResults(6);

        return $qb->getQuery()->getResult();
    }

    public function findByCategory(Category $category): array
    {
        $qb = $this->getQb()
            ->join('g.categories', 'cat')
            ->andWhere('cat = :category')
            ->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }

    public function findByCommentAmount()
    {
        $qb = $this->getQb()
            ->join('g.reviews', 'r')
            ->groupBy('g.slug')
            ->orderBy('COUNT(r.id)', 'DESC')
            ->setMaxResults(6);

        return $qb->getQuery()->getResult();
    }

    public function findLatest(int $int)
    {
        $qb = $this->getQb()
            ->orderBy('g.publishedAt', 'DESC')
            ->setMaxResults($int);

        return $qb->getQuery()->getResult();
    }
}

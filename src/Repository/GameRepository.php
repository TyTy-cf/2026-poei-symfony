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

    public function findByGameTimeSum(?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.userOwnGames', 'uog')
            ->groupBy('g')
            ->orderBy('SUM(uog.gameTime)', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByBestRating(?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.reviews', 'r')
            ->groupBy('g')
            ->orderBy('AVG(r.rating)', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneFullBy(string $slug): ?Game
    {
        return $this->createQueryBuilder('g')
            ->select('g', 'ca', 'r', 'p', 'co', 'u')
            ->join('g.categories', 'ca')
            ->join('g.publisher', 'p')
            ->join('g.countries', 'co')
            ->join('g.reviews', 'r')
            ->join('r.user', 'u')
            ->where('g.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('r.rating', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findBySimilarCategory(Game $game, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.categories', 'c')
            // WHERE c.id IN (1, 5, 6, 8)
            ->where('c IN (:categs)')
            ->setParameter('categs', $game->getCategories())
            ->orderBy('g.price', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

}

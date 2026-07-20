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
            ->leftJoin('g.userOwnGames', 'uog')
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
            ->leftJoin('g.reviews', 'r')
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
            ->leftJoin('g.categories', 'ca')
            ->leftJoin('g.publisher', 'p')
            ->leftJoin('g.countries', 'co')
            ->leftJoin('g.reviews', 'r')
            ->leftJoin('r.user', 'u')
            ->where('g.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('r.rating', 'DESC')
            ->addOrderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findBySimilarCategory(Game $game, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.categories', 'c')
            ->where('c IN (:categs)')
            ->setParameter('categs', $game->getCategories())
            ->andWhere('g != :game')
            ->setParameter('game', $game)
            ->groupBy('g')
            ->orderBy('g.price', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findLatestGames(?int $limit = null):array
    {
        $qb = $this->createQueryBuilder("g")
            ->join("g.publisher", "p")
            ->orderBy("g.publishedAt", "DESC");

            if ($limit !== null) {
                $qb->setMaxResults($limit);
            }

        return $qb->getQuery()->getResult();

    }

}

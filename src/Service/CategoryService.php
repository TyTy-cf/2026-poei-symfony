<?php

namespace App\Service;

use App\Entity\Category;
use App\Service\SlugifyService;
use Doctrine\ORM\EntityManagerInterface;

readonly class CategoryService
{

    public function __construct(
        private EntityManagerInterface $em,
        private SlugifyService         $slugService,
    )
    {
    }

    public function persistCategory(Category $category): bool
    {
        try {
            $category->setSlug($this->slugService->slugify($category->getName()));

            // Must set the inverse of the relation to persist it in DB
            foreach ($category->getGames() as $game) {
                $game->addCategory($category);
            }

            if ($category->getId() === null) {
                $this->em->persist($category);
            }

            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}

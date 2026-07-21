<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{

    public function __construct(private EntityManagerInterface $em, private Slugger $slugger)
    {
    }

    public function persistCategory(Category $category): bool
    {

        try {
            $isEdit = $category->getId() !== null;
            $category->setSlug($this->slugger->slugify($category->getName()));
            foreach ($category->getGames() as $game) {
                $game->addCategory($category);
            }
            if (!$isEdit) {
                $this->em->persist($category);
            }
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

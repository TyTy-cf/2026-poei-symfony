<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryFormService
{
  public function save(Category $category, EntityManagerInterface $em, SlugifyService $slugifyService): void
  {
    $category->setSlug($slugifyService->slugify($category->getName()));

    $em->persist($category);
    $em->flush();
  }
}

<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

readonly class FooterRuntime implements RuntimeExtensionInterface
{

    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function bestCategories(): array
    {
        return $this->categoryRepository->findBestCategories(5);
    }

}

<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class FooterRuntime implements RuntimeExtensionInterface
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        // Inject dependencies if needed
    }

    public function bestCategories()
    {
        return $this->
    }
}

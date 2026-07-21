<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class ClassRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getClassName($value) : string
    {
        return str_replace('app\\entity\\', '', strtolower(get_class($value)));
    }
}

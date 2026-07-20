<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TimeRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function secondsToGameTime(int $value): string
    {
        $hours = floor($value / 3600);
        $minutes = floor($value / 60 % 60);

        return $hours . " h " . $minutes . " m ";
    }
}

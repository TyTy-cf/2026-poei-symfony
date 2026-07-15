<?php

namespace App\Twig\Runtime;

use App\Service\TimeService;
use Twig\Extension\RuntimeExtensionInterface;

readonly class TimeRuntime implements RuntimeExtensionInterface
{

  public function __construct(
    private TimeService $timeService
  ) {}

  public function timeConversion(int $timeInSec): string
  {
    return $this->timeService->timeConversion($timeInSec);
  }
}

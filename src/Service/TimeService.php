<?php

namespace App\Service;

class TimeService
{

    public function timeConversion(int $timeInSec): string
    {
        $hours = floor($timeInSec / 3600);
        $minutes = floor($timeInSec / 60 % 60);

        if ($hours < 10) {
            $hours = '0' . $hours;
        }

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        return $hours . 'h' . $minutes;
    }

}

<?php

namespace App\Service;

class FormatService
{
    /**
     * @param \DateTime $month
     * @return array
     */
    public static function formatDatesBorders(\DateTime $month): array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0, 0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);
        return array($fromFormat, $toFormat);
    }
}
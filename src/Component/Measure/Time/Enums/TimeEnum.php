<?php

namespace Untek\Component\Measure\Time\Enums;

/**
 * Единицы измерения времени
 */
class TimeEnum
{

    const SECOND_PER_MILLISECOND = 1 / 1000;
    const SECOND_PER_MICROSECOND = self::SECOND_PER_MILLISECOND / 1000;
    const SECOND_PER_SECOND = 1;
    const SECOND_PER_MINUTE = 60;
    const SECOND_PER_HOUR = self::SECOND_PER_MINUTE * 60;
    const SECOND_PER_DAY = self::SECOND_PER_HOUR * 24;
    const SECOND_PER_WEEK = self::SECOND_PER_DAY * 7;
    const SECOND_PER_MONTH = self::SECOND_PER_DAY * 30;
    const SECOND_PER_YEAR = self::SECOND_PER_DAY * 365;

}

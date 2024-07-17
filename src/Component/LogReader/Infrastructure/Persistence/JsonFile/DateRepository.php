<?php

namespace Untek\Component\LogReader\Infrastructure\Persistence\JsonFile;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Core\FileSystem\Helpers\FindFileHelper;

class DateRepository
{

    public function __construct(private string $directory)
    {
    }

    public function findLast()
    {
        $all = $this->findAll();
        return ArrayHelper::first($all);
    }

    public function findAll()
    {
        $dates = FindFileHelper::scanDir($this->directory);
        rsort($dates);
        $dates = array_slice($dates, 0, 30);
        foreach ($dates as $index => $date) {
            if (FilePathHelper::fileExt($date) == 'log') {
                $dates[$index] = FilePathHelper::fileRemoveExt($date);
            } else {
                unset($dates[$index]);
            }
        }
        return $dates;
    }
}
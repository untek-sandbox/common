<?php

namespace Untek\Utility\GoogleTranslate\Application;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class DataToFlat
{

//    private static $id = 0;
//    private static $lines = [];
//    protected $placeholders = [];
//    protected $placeholderNumber = 1;

    public function run(array $data) {
//        self::$id = 0;
        $lines = [];
        $this->toFlat($data, $lines);
//        dd($lines);
        return implode(PHP_EOL, $lines);
    }

    private function toFlat(array $data, &$lines = [])
    {
        $d = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {

//                $value = self::$lines[self::$id];

                $d[$key] = $value;
                $lines[] = $value;

//                self::$id++;
            } elseif (is_array($value)) {
                $d[$key] = $this->toFlat($value, $lines);
            } else {
                $d[$key] = $value;
            }
        }

//        file_put_contents('/home/server/www/forecast-kz/map-backend/config/ru.min.txt', implode(PHP_EOL, $text));

        return $d;
    }
}

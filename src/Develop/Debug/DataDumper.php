<?php

namespace Untek\Develop\Debug;

use Symfony\Component\VarExporter\VarExporter;

class DataDumper
{

    public static function print(mixed $data, ?string $format = 'php')
    {
        if ($format == 'php') {
            $data = VarExporter::export($data);
        } elseif ($format == 'json') {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } elseif ($format == null) {
            $data = print_r($data, true);
        }
        echo $data;
        echo PHP_EOL;
        exit();
    }
}

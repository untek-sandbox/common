<?php

namespace Untek\Database\Base\Domain\Helpers;

use GuzzleHttp\Psr7\Uri;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;

class ConfigHelper
{

    public static function parseDsn(string $dsn): array
    {
        $dsnConfig = parse_url($dsn);
        $dsnConfig = array_map('rawurldecode', $dsnConfig);
        $connectionCofig = [
            'driver' => ArrayHelper::getValue($dsnConfig, 'scheme'),
            'host' => ArrayHelper::getValue($dsnConfig, 'host', '127.0.0.1'),
            'database' => trim(ArrayHelper::getValue($dsnConfig, 'path'), '/'),
            'username' => ArrayHelper::getValue($dsnConfig, 'user'),
            'password' => ArrayHelper::getValue($dsnConfig, 'pass'),
        ];
        return $connectionCofig;
    }

    public static function generateDsn(array $connectionCofig): string
    {
        $uri = new Uri();

// postgresql://myuser:mypassword@localhost:5634/lock
        if ($connectionCofig['driver'] === DbDriverEnum::PGSQL) {
//            dd($connectionCofig['database']);

            $uri = $uri
                ->withScheme('postgresql')
                ->withHost($connectionCofig['host'])
                ->withPort($connectionCofig['port'] ?? 5432)
                ->withUserInfo($connectionCofig['username'], $connectionCofig['password'])
                ->withPath('/' . $connectionCofig['database'])
            ;
        }

//        dd($uri->__toString());

        return $uri->__toString();
    }

    public static function prepareConfig($connection)
    {
        if (!empty($connection['database'])) {
            $connection['database'] = rtrim($connection['database'], '/');
        }
        if (!empty($connection['read']['host'])) {

            $connection['read']['host'] = explode(',', $connection['read']['host']);
        }
        if (!empty($connection['write']['host'])) {
            $connection['write']['host'] = explode(',', $connection['write']['host']);
        }
        return $connection;
    }
}
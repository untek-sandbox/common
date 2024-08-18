<?php

namespace Untek\Kaz\Eds;

use Untek\Core\Bundle\Base\BaseBundle;
use Untek\Framework\Console\Symfony4\Libs\CommandConfigurator;
use Untek\Kaz\Eds\Commands\CrlRefreshCommand;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'eds';
    }

    /*public function console(): array
    {
        return [
            'Untek\Kaz\Eds\Commands',
        ];
    }*/

    public function consoleCommands(CommandConfigurator $commandConfigurator)
    {
        $commandConfigurator->registerCommandClass(CrlRefreshCommand::class);
    }

    public function migration(): array
    {
        return [
            __DIR__ . '/Domain/Migrations',
        ];
    }
    
    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container.php',
        ];
    }

    public function entityManager(): array
    {
        return [
            __DIR__ . '/Domain/config/em.php',
        ];
    }
}

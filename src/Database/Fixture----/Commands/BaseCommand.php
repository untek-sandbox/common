<?php

namespace Untek\Database\Fixture\Commands;

use Untek\Database\Fixture\Domain\Services\FixtureService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

abstract class BaseCommand extends Command
{

    protected $fixtureService;

    public function __construct(FixtureService $fixtureService)
    {
        parent::__construct(self::$defaultName);
        $this->fixtureService = $fixtureService;
    }

    protected function configure()
    {
        $this
            ->addOption(
                'withConfirm',
                null,
                InputOption::VALUE_REQUIRED,
                'Your selection migrations',
                true
            );
    }
}

<?php

namespace Untek\Framework\Socket\Symfony4\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Socket\Domain\Libs\SocketDaemon;

class SocketCommand extends Command
{

    protected static $defaultName = 'socket:worker';
    private $socketDaemon;

    public function __construct(SocketDaemon $socketDaemon)
    {
        parent::__construct(self::$defaultName);
        $this->socketDaemon = $socketDaemon;
    }

    protected function configure()
    {
        $this->addArgument('workerCommand', InputArgument::OPTIONAL);
        $this->addOption(
            'daemon',
            'd',
            InputOption::VALUE_NONE,
            'Run as daemon'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $argv;
        $argv[1] = $input->getArgument('workerCommand');
        $daemon = $input->getOption('daemon');
        $this->socketDaemon->runAll($daemon);
        return Command::SUCCESS;
    }
}

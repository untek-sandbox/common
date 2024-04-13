<?php

namespace Untek\Framework\Socket\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;

class SocketCommand extends Command
{

    public function __construct(private SocketDaemonInterface $socketDaemon)
    {
        parent::__construct();
    }

    public static function getDefaultName(): ?string
    {
        return 'socket:worker';
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

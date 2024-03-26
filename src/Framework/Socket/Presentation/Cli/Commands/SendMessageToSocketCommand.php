<?php

namespace Untek\Framework\Socket\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Socket\Application\Commands\SendMessageToWebSocketCommand;
use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

class SendMessageToSocketCommand extends Command
{

    private SocketDaemonInterface $socketDaemon;

    public function __construct(SocketDaemonInterface $socketDaemon, private CommandBusInterface $bus)
    {
        parent::__construct();
        $this->socketDaemon = $socketDaemon;
    }

    public static function getDefaultName(): ?string
    {
        return 'socket:send-message';
    }

    protected function configure()
    {
        $this->addArgument('userId', InputArgument::REQUIRED);
        $this->addArgument('eventName', InputArgument::REQUIRED);
        $this->addArgument('payload', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $eventName = $input->getArgument('eventName');
        $payload = $input->getArgument('payload');

        $command = new SendMessageToWebSocketCommand($eventName, 1, $userId, $payload);
        $this->bus->handle($command);

        /*$event = new SocketEvent();
        $event->setUserId($userId);
        $event->setName($eventName);
        $event->setPayload($payload);
        $this->socketDaemon->sendMessageToTcp($event);*/

        return Command::SUCCESS;
    }
}

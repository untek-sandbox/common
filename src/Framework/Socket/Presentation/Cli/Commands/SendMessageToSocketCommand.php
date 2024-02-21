<?php

namespace Untek\Framework\Socket\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

class SendMessageToSocketCommand extends Command
{

    private SocketDaemonInterface $socketDaemon;

    public function __construct(SocketDaemonInterface $socketDaemon)
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
        $this->addArgument('message', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $message = $input->getArgument('message');

        $this->sendMessageToUser($userId, 'taxi.orderCreated', $message);

        return Command::SUCCESS;
    }

    protected function sendMessageToUser(int $userId, string $eventName, mixed $payload)
    {
        $event = new SocketEvent();
        $event->setUserId($userId);
        $event->setName($eventName);
        $event->setPayload($payload);
        $this->socketDaemon->sendMessageToTcp($event);
    }
}

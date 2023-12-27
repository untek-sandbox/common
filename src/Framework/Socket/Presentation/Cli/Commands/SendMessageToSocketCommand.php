<?php

namespace Untek\Framework\Socket\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Socket\Domain\Enums\SocketEventEnum;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;
use Untek\Framework\Socket\Infrastructure\Services\SocketDaemon;
use DateTime;

class SendMessageToSocketCommand extends Command
{

    private SocketDaemon $socketDaemon;

    public function __construct(SocketDaemon $socketDaemon)
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
        $this->addArgument('message', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $input->getArgument('message');

        // заказ создан
        $this->sendMessageToUser(1, 'taxi.orderCreated', [
            'orderId' => 111,
            'points' => [],
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // водитель назначен
        $this->sendMessageToUser(1, 'taxi.driverAssigned', [
            'orderId' => 111,
            'driver' => [
                'id' => 123,
                'name' => 'Valera',
            ],
            'car' => [
                'id' => 456,
                'brand' => 'Toyota',
                'model' => 'Camry',
                'number' => 'A123BWM09',
                'color' => 'yellow',
            ],
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // водитель приехал к точке А
        $this->sendMessageToUser(1, 'taxi.driverArrived', [
            'orderId' => 111,
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // вы в пути
        $this->sendMessageToUser(1, 'taxi.youStarted', [
            'orderId' => 111,
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // вы приехали
        $this->sendMessageToUser(1, 'taxi.youFinished', [
            'orderId' => 111,
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // заказ отменен водителем
        $this->sendMessageToUser(1, 'taxi.orderCancelledByDriver', [
            'orderId' => 111,
            'canceledBy' => [
                'id' => 123,
                'name' => 'Valera',
            ],
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

        // заказ отменен пассажиром
        $this->sendMessageToUser(1, 'taxi.orderCancelledByPassenger', [
            'orderId' => 111,
            'canceledBy' => [
                'id' => 333,
                'name' => 'Maria',
            ],
            'time' => (new DateTime())->format(DateTime::ISO8601),
        ]);

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

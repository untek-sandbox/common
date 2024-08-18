<?php

namespace Untek\Utility\Logger\Infrastructure\Handlers;

use DateTimeImmutable;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Level;
use Untek\Database\Eloquent\Domain\Capsule\Manager;

class EloquentHandler extends AbstractProcessingHandler
{

    public function __construct(
        private Manager $capsule,
        int|string|Level $level = Level::Debug,
        bool $bubble = true
    )
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $connection = $this->capsule->getConnection('default');
        $qb = $connection->table('log');
        $item = [
            'level' => $record['level'],
            'channel' => $record['channel'],
            'message' => $record['message'],
            'datetime' => $record['datetime']->format(DateTimeImmutable::ISO8601),
            'formatted' => $record['formatted'],
            'extra' => json_encode($record['extra'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'context' => json_encode($record['context'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ];
        $qb->insert($item);
    }
}

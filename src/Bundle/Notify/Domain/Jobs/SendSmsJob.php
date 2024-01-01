<?php

namespace Untek\Bundle\Notify\Domain\Jobs;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\SmsRepositoryInterface;
use Untek\Bundle\Queue\Domain\Interfaces\JobInterface;
use Psr\Container\ContainerInterface;

class SendSmsJob implements JobInterface
{

    /** @var SmsEntity */
    public $entity;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        /** @var SmsRepositoryInterface $repository */
        $repository = $this->container->get(SmsRepositoryInterface::class);
        $repository->send($this->entity);
    }
}
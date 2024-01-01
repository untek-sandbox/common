<?php

namespace Untek\Bundle\Notify\Domain\Services;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;
use Untek\Bundle\Notify\Domain\Enums\ChannelEnum;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\SmsRepositoryInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\SmsServiceInterface;
use Untek\Bundle\Notify\Domain\Jobs\SendSmsJob;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;
use Untek\Bundle\Queue\Domain\Interfaces\Services\JobServiceInterface;
use Psr\Container\ContainerInterface;

class SmsService implements SmsServiceInterface
{

    private $smsRepository;
    private $jobService;
    private $container;

    public function __construct(
        SmsRepositoryInterface $smsRepository,
        JobServiceInterface $jobService,
        ContainerInterface $container
    )
    {
        $this->smsRepository = $smsRepository;
        $this->jobService = $jobService;
        $this->container = $container;
    }

    public function push(SmsEntity $emailEntity, $priority = PriorityEnum::NORMAL)
    {
        $emailJob = new SendSmsJob($this->container);
        $emailJob->entity = $emailEntity;
        $pushResult = $this->jobService->push($emailJob, $priority, ChannelEnum::SMS);
    }

}

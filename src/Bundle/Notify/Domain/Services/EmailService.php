<?php

namespace Untek\Bundle\Notify\Domain\Services;

use Psr\Container\ContainerInterface;
use Untek\Bundle\Notify\Domain\Entities\EmailEntity;
use Untek\Bundle\Notify\Domain\Enums\ChannelEnum;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\EmailRepositoryInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\EmailServiceInterface;
use Untek\Bundle\Notify\Domain\Jobs\SendEmailJob;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;
use Untek\Bundle\Queue\Domain\Interfaces\Services\JobServiceInterface;

class EmailService implements EmailServiceInterface
{

    private $emailRepository;
    private $jobService;
    private $container;

    public function __construct(EmailRepositoryInterface $emailRepository, JobServiceInterface $jobService, ContainerInterface $container)
    {
        $this->emailRepository = $emailRepository;
        $this->jobService = $jobService;
        $this->container = $container;
    }

    public function push(EmailEntity $emailEntity, $priority = PriorityEnum::NORMAL)
    {
        if($emailEntity->getFrom() == null) {
            $emailEntity->setFrom(getenv('EMAIL_FROM'));
        }
        $emailJob = new SendEmailJob($this->container);
        $emailJob->entity = $emailEntity;
        $pushResult = $this->jobService->push($emailJob, $priority, ChannelEnum::EMAIL);
    }

}

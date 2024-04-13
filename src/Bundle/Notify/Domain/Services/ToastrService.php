<?php

namespace Untek\Bundle\Notify\Domain\Services;

use Untek\Bundle\Notify\Domain\Entities\ToastrEntity;
use Untek\Bundle\Notify\Domain\Enums\FlashMessageTypeEnum;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\ToastrRepositoryInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Service\Base\BaseService;
use Untek\Component\I18Next\Facades\I18Next;

class ToastrService extends BaseService implements ToastrServiceInterface
{

    const DEFAULT_DELAY = 5000;

    public function __construct(ToastrRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    public function success($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::SUCCESS, $message, $delay);
    }

    public function info($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::INFO, $message, $delay);
    }

    public function warning($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::WARNING, $message, $delay);
    }

    public function error($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::ERROR, $message, $delay);
    }

    public function add(string $type, $message, int $delay = null)
    {
        if ($delay == null) {
            $delay = self::DEFAULT_DELAY;
        }
        if (is_array($message)) {
            $message = I18Next::translateFromArray($message);
        }
        $toastrEntity = new ToastrEntity();
        $toastrEntity->setType($type);
        $toastrEntity->setContent($message);
        $toastrEntity->setDelay($delay);
        $this->getRepository()->create($toastrEntity);
    }

    public function clear()
    {
        $this->getRepository()->clear();
    }

    public function findAll(): Enumerable
    {
        return $this->getRepository()->findAll();
    }
}

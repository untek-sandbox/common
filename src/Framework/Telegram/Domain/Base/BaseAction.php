<?php

namespace Untek\Framework\Telegram\Domain\Base;

use Untek\Core\Container\Libs\Container;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Services\ResponseService;
use Untek\Framework\Telegram\Domain\Services\SessionService;
use Untek\Framework\Telegram\Domain\Services\StateService;
use Untek\Framework\Telegram\Domain\Services\UserService;

abstract class BaseAction
{

    /** @var SessionService */
    protected $session;

    /** @var StateService */
    protected $state;

    /** @var ResponseService */
    protected $response;

//    public function __construct()
//    {
//        $container = ContainerHelper::getContainer();
//        //$this->session = $container->get(SessionService::class);
//        //$this->state = $container->get(StateService::class);
//        /** @var ResponseService $response */
//        $this->response = $container->get(ResponseService::class);
//        //$this->response = new ResponseService($messages, $container->get(UserService::class));
//    }

    public function setResponseService(ResponseService $responseService): void
    {
        $this->response = $responseService;
    }

    public function stateName()
    {
        return null;
    }

    abstract public function run(RequestEntity $requestEntity);

}

<?php


namespace Untek\Framework\Telegram\Domain\Services;

use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Repositories\Http\RequestRepository;

class RequestService
{

    /** @var RequestRepository */
    private $requestRepository;

    /** @var RequestEntity */
    private $requestEntity;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function getRequest(): RequestEntity {
        if(empty($this->requestEntity)) {
            $this->requestEntity = $this->requestRepository->getRequest();
        }
        return $this->requestEntity;
    }

    public function getToken() {
        return $this->requestRepository->getToken();
    }
}

/*
"update_id": 692451587,
"message": {
    "message_id": 3739,
    "from": {
        "id": 123,
        "is_bot": false,
        "first_name": "user123",
        "username": "user123",
        "language_code": "ru"
    },
    "chat": {
        "id": 123,
        "first_name": "user123",
        "username": "user123",
        "type": "private"
    },
    "date": 1592736037,
    "text": "cxvxc"
}
*/

<?php

namespace Untek\Kaz\Egov\Qr\Factories;

use Untek\Kaz\Egov\Qr\Services\EncoderService;
use Untek\Kaz\Egov\Qr\Wrappers\XmlWrapper;

class EncoderServiceFactory
{

    public static function createServiceForEgov(int $maxQrSize = 1183): EncoderService
    {
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = new EncoderService($wrapper, ['zip'], $maxQrSize);
        return $encoderService;
    }
}
<?php

namespace Untek\Kaz\Egov\Factories;

use Untek\Lib\QrBox\Factories\ClassEncoderFactory;
use Untek\Lib\QrBox\Factories\EncoderServiceFactory;
use Untek\Lib\QrBox\Services\EncoderService;
use Untek\Kaz\Egov\Wrappers\XmlWrapper;

class EgovEncoderServiceFactory
{

    public static function createService(int $maxQrSize = 1183): EncoderService
    {
        $classEncoder = ClassEncoderFactory::create();
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $wrappers = [
            XmlWrapper::class,
        ];
        $resultEncoders = ['zip'];
        return EncoderServiceFactory::createService($resultEncoders, $wrappers, $wrapper, $classEncoder, $maxQrSize);
    }
}
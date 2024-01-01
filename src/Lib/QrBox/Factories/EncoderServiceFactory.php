<?php

namespace Untek\Lib\QrBox\Factories;

use Untek\Lib\QrBox\Libs\ClassEncoder;
use Untek\Lib\QrBox\Libs\DataSize;
use Untek\Lib\QrBox\Libs\WrapperDetector;
use Untek\Lib\QrBox\Services\EncoderService;
use Untek\Lib\QrBox\Wrappers\WrapperInterface;

class EncoderServiceFactory
{

    public static function createService(array $resultEncoders, array $wrappers, WrapperInterface $wrapper, ClassEncoder $classEncoder, int $maxQrSize = 1183): EncoderService
    {
        $wrapperDetector = new WrapperDetector($wrappers);
        $resultEncoder = $classEncoder->encodersToClasses($resultEncoders);
        $wrapperEncoder = $classEncoder->encodersToClasses($wrapper->getEncoders());
        $dataSize = new DataSize($wrapperEncoder, $wrapper, $maxQrSize);
        $encoderService = new EncoderService(
            $wrapperDetector,
            $resultEncoder,
            $wrapperEncoder,
            $wrapper,
            $dataSize
        );
        return $encoderService;
    }
}
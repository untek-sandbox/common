<?php

namespace Untek\Kaz\Egov\Facades;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Kaz\Egov\Factories\EgovEncoderServiceFactory;
use Untek\Lib\QrBox\Entities\FileEntity;
use Untek\Lib\QrBox\Services\QrService;

class QrFacade
{

    /**
     * @param string $content
     * @param int $margin
     * @param int $size
     * @param int $maxQrSize
     * @param string $qrFormat
     * @return \Untek\Core\Collection\Interfaces\Enumerable | FileEntity[]
     */
    public static function generateQrCode(string $content, int $margin = 1, int $size = 500, int $maxQrSize = 1183, string $qrFormat = 'png'): Enumerable
    {
        $encoderService = EgovEncoderServiceFactory::createService($maxQrSize);
        $encoded = $encoderService->encode($content);
        $qrService = new QrService($qrFormat, $margin, $size);
        return $qrService->encode($encoded);
    }
}

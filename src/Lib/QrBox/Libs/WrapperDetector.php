<?php

namespace Untek\Lib\QrBox\Libs;

use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Lib\QrBox\Wrappers\WrapperInterface;

class WrapperDetector
{

    private $wrappers;

    public function __construct(array $wrappers)
    {
        $this->wrappers = $wrappers;
    }

    /**
     * @param string $encoded
     * @return WrapperInterface
     * @throws \Exception
     */
    public function detect(string $encoded): WrapperInterface
    {
        foreach ($this->wrappers as $wrapperClass) {
            $wrapperInstance = $this->createEncoder($wrapperClass);
            $isDetected = $wrapperInstance->isMatch($encoded);
            if ($isDetected) {
                return $wrapperInstance;
            }
        }
        throw new \Exception('Wrapper not detected!');
    }

    private function createEncoder($wrapperClass): WrapperInterface
    {
        return InstanceHelper::create($wrapperClass);
    }
}
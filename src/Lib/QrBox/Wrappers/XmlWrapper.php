<?php

namespace Untek\Lib\QrBox\Wrappers;


use Untek\Component\Encoder\Encoders\XmlEncoder;
use Untek\Lib\QrBox\Entities\BarCodeEntity;
use DateTime;

class XmlWrapper extends BaseWrapper implements WrapperInterface
{

    public function isMatch(string $encodedData): bool
    {
        return preg_match('#<\?xml#i', $encodedData);
    }

    public function encode(BarCodeEntity $entity): string
    {
        $barCode = [];
        $barCode['id'] = $entity->getId();
        $barCode['count'] = $entity->getCount();
        $barCode['data'] = $entity->getData();
        $barCode['createdAt'] = $entity->getCreatedAt()->format(DateTime::RFC3339_EXTENDED);

        $xmlEncoder = new XmlEncoder();
        $encoded = $xmlEncoder->encode(['BarCode' => $barCode]);
        $encoded = $this->cleanXml($encoded);
        return $encoded;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $xmlEncoder = new XmlEncoder();
        $decoded = $xmlEncoder->decode($encodedData);
        $barCode = $decoded['BarCode'];
        $entity = new BarCodeEntity();
        $entity->setId($barCode['id']);
        $entity->setCount($barCode['count']);
        $entity->setData($barCode['data']);
        $entity->setCreatedAt(new DateTime($barCode['createdAt']));
        return $entity;
    }

    protected function cleanXml(string $xml): string
    {
        $xml = trim($xml);
        $xml = preg_replace('/(\>\s+\<)/i', '><', $xml);
        return $xml;
    }
}

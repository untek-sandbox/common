<?php

namespace Untek\FrameworkPlugin\HttpAuthentication\Application\Services;

use DomainException;

class SignCookieEncoder
{
    public function __construct(private string $secret)
    {
    }

    public function encode(mixed $value): string
    {
        $data = [
            'value' => $value,
            'hash' => $this->generateHash($value),
        ];
        return json_encode($data);
    }

    /**
     * @param string $encodedValue
     * @return mixed
     */
    public function decode(string $encodedValue): mixed
    {
        $json = json_decode($encodedValue, JSON_OBJECT_AS_ARRAY);
        $hash = $this->generateHash($json['value']);
        if ($hash !== $json['hash']) {
            throw new DomainException('Bad check cookie secure!');
        }
        return $json['value'];
    }

    private function generateHash($value): string
    {
        $jsonValue = json_encode($value);
        $scopedValue = $this->secret . $jsonValue;
        return hash('sha256', $scopedValue);
    }
}

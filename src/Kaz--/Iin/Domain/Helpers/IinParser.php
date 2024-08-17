<?php

namespace Untek\Kaz\Iin\Domain\Helpers;

use Untek\Kaz\Iin\Domain\Entities\BaseEntity;
use Untek\Kaz\Iin\Domain\Enums\TypeEnum;
use Untek\Kaz\Iin\Domain\Exceptions\BadTypeException;
use Untek\Kaz\Iin\Domain\Libs\Parsers\IndividualParser;
use Untek\Kaz\Iin\Domain\Libs\Parsers\JuridicalParser;
use Untek\Kaz\Iin\Domain\Libs\Parsers\ParserInterface;
use Untek\Kaz\Iin\Domain\Libs\Validator;

class IinParser
{

    public static function parse(?string $value): BaseEntity
    {
        $validator = new Validator();
        $validator->validate($value);
        $type = self::getType($value);
        $parser = self::getParserByType($type);
        return $parser->parse($value);
    }

    private static function getParserByType(string $type): ParserInterface
    {
        if ($type == TypeEnum::INDIVIDUAL) {
            return new IndividualParser();
        } elseif ($type == TypeEnum::JURIDICAL) {
            return new JuridicalParser();
        }
    }

    private static function getType(string $value): string
    {
        $typeMarker = $value[4];
        if (in_array($typeMarker, [0, 1, 2, 3])) {
            return TypeEnum::INDIVIDUAL;
        }
        if (in_array($typeMarker, [4, 5, 6])) {
            return TypeEnum::JURIDICAL;
        }
        throw new BadTypeException('Error type');
    }
}

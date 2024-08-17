<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\BaseEntity;

interface ParserInterface
{

    public function parse(string $value): BaseEntity;
}

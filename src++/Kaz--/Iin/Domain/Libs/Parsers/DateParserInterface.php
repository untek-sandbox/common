<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\DateEntity;

interface DateParserInterface
{

    public function parse(string $value): DateEntity;
}

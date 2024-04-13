<?php

namespace Untek\Utility\CodeGenerator\Application\Interfaces;

use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

interface InteractInterface
{

    public function input(SymfonyStyle $io): array;
}
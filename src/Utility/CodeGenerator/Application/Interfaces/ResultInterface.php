<?php

namespace Untek\Utility\CodeGenerator\Application\Interfaces;

interface ResultInterface
{

    public function getName(): string;

    public function getContent(): string;
}
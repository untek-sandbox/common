<?php

namespace Untek\Component\Office\Domain\Interfaces\Services;


use Untek\Domain\Service\Interfaces\CrudServiceInterface;
use Untek\Component\Office\Domain\Entities\DocXEntity;

interface DocXServiceInterface extends CrudServiceInterface
{

    public function findOneByFileName(string $fileName) : DocXEntity;

    public function createByFileName(string $fileName, DocXEntity $docXEntity);

    public function renderEntity(DocXEntity $docXEntity, array $params = []): void;

    public function render(string $temlateFile, array $params = []): DocXEntity;

    public function renderToFile(string $temlateFile, $resultFile, array $params = []): void;
}


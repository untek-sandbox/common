<?php

namespace Untek\Sandbox\Sandbox\Zip\Domain\Libs;

use Exception;
use Symfony\Component\Uid\Uuid;
use ZipArchive;
use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Core\FileSystem\Helpers\FindFileHelper;

class ZipDirectory
{

    public function createZipFromDirectory(string $directory, string $zipFileName = 'arch.zip')
    {
        $files = FindFileHelper::scanDir($directory);
        $tmpDir = $this->getTmpDirectory();
        $zipFile = $tmpDir . '/' . $zipFileName;
        $zipArchive = $this->openZip($zipFile);
        foreach ($files as $fileName) {
            $zipArchive->addFile($directory . '/' . $fileName, $fileName);
        }
        $zipArchive->close();
        return $zipFile;
    }

    public function createZipFromFileArray(array $files, string $zipFileName = 'arch.zip')
    {
        $tmpDir = $this->getTmpDirectory();
        $zipFile = $tmpDir . '/' . $zipFileName;
        $zipArchive = $this->openZip($zipFile);
        foreach ($files as $fileName => $fileContent) {
            $zipArchive->addFromString($fileName, $fileContent);
        }
        $zipArchive->close();
        return $zipFile;
    }

    private function getTmpDirectory(): string
    {
        $tmpDir = sys_get_temp_dir() . '/' . Uuid::v4()->toRfc4122();
        FileHelper::createDirectory($tmpDir);
        return $tmpDir;
    }

    private function openZip(string $zipFile): ZipArchive
    {
        $zipArchive = new ZipArchive();
        $resource = $zipArchive->open($zipFile, ZipArchive::CREATE);
        if ($resource !== TRUE) {
            throw new Exception('Zip not opened!');
        }
        return $zipArchive;
    }
}

<?php

namespace Untek\Crypt\Pki\XmlDSig\Domain\Libs\KeyLoaders;

use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Core\FileSystem\Helpers\FileStorageHelper;
use Untek\Core\FileSystem\Helpers\FindFileHelper;
use Untek\Crypt\Pki\XmlDSig\Domain\Entities\KeyEntity;
use Untek\Domain\Entity\Helpers\EntityHelper;

class DirectoryKeyLoader
{

    private $directory;
    private $names = [
        'certificate' => 'certificate.pem',
        'certificateRequest' => 'certificateRequest.pem',
        'csr' => 'certificateRequest.pem',
        'privateKeyPassword' => 'password.txt',
        'privateKey' => 'private.pem',
        'publicKey' => 'public.pem',
        'p12' => 'rsa.p12',
    ];

    public function __construct(string $directory = null)
    {
        $this->directory = $directory;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function findAll(): Enumerable
    {
        $files = FindFileHelper::scanDir($this->directory);
        $collection = new Collection();
        foreach ($files as $file) {
            $fileNmae = $this->directory . '/' . $file;
            if (is_dir($fileNmae)) {
                $keyEntity = $this->load($file);
                $collection->add($keyEntity);
            }
        }
        return $collection;
    }

    public function remove(string $name): void
    {
        $directory = $this->directory . '/' . $name;
        FileHelper::removeDirectory($directory);
        FileHelper::createDirectory($directory);
    }

    public function load(string $name): KeyEntity
    {
        $directory = $this->directory . '/' . $name;

        $data = [];
        foreach ($this->names as $attributeName => $fileName) {
            $file = $directory . '/' . $fileName;
            if (file_exists($file)) {
                $data[$attributeName] = FileStorageHelper::load($file);
            }
        }

        $userKeyEntity = new KeyEntity;
        PropertyHelper::setAttributes($userKeyEntity, $data);
        $userKeyEntity->setName($name);
        return $userKeyEntity;
    }

    public function save(string $name, KeyEntity $keyEntity): void
    {
        $directory = $this->directory . '/' . $name;
        $data = PropertyHelper::toArray($keyEntity);
        unset($data['name']);
        foreach ($data as $attributeName => $value) {
            if (!empty($value)) {
                $fileName = $this->names[$attributeName];
                $file = $directory . '/' . $fileName;
                FileStorageHelper::save($file, $value);
            }
        }
    }
}

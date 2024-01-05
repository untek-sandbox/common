<?php

namespace Untek\Lib\Components\Zip\Libs;

use App\Common\Base\BaseController;
use Exception;
use Untek\Core\FileSystem\Helpers\FileStorageHelper;
use ZipArchive;

class Zip
{

    private $resource;
    private $zipArchive;

    public function __construct(string $zipFile)
    {
        $this->zipArchive = new ZipArchive();
        FileStorageHelper::touchDirectory(dirname($zipFile));
        /*if(!is_dir(dirname($zipFile))) {
            mkdir(dirname($zipFile));
        }*/
        $this->resource = $this->zipArchive->open($zipFile, ZipArchive::CREATE);
        if ($this->resource === TRUE) {

        } else {
            throw new Exception('Zip not opened!');
        }
    }

    public function glob(string $pattern)
    {
        $resultFiles = [];
        $fileList = $this->files();
        foreach ($fileList as $file) {
            $isMatch = fnmatch($pattern, $file);
            if ($isMatch) {
                $resultFiles[] = $file;
            }
        }
        return $resultFiles;
    }

    public function getFilesContent(array $fileList)
    {
        $props = [];
        foreach ($fileList as $file) {
            $props[$file] = $this->readFile($file);
        }
        return $props;
    }

    public function getDirectoryFiles(string $directory)
    {
        $props = [];
        $fileList = $this->glob($directory . '/*');
        foreach ($fileList as $file) {
            $name = str_replace($directory . '/', '', $file);
            $props[$name] = $this->readFile($file);
        }
        return $props;
    }

    public function files()
    {
        $i = 0;
        $list = array();
        while ($name = $this->zipArchive->getNameIndex($i)) {
            $list[$i] = $name;
            $i++;
        }
        return $list;
    }

    public function readFile($name)
    {
        return $this->zipArchive->getFromName($name);
    }

    public function writeFile($name, $content)
    {
        return $this->zipArchive->addFromString($name, $content);
    }

    public function close()
    {
        $this->zipArchive->close();
    }
}

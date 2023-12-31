<?php

namespace Untek\Component\Office\Domain\Services;


use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Domain\Service\Base\BaseCrudService;
use Untek\Component\Office\Domain\Entities\DocXEntity;
use Untek\Component\Office\Domain\Enums\AttributeEnum;
use Untek\Component\Office\Domain\Interfaces\Services\DocXServiceInterface;
use Untek\Lib\Components\Zip\Libs\Zip;

/**
 * @method
 * DocXRepositoryInterface getRepository()
 */
class DocXService extends BaseCrudService implements DocXServiceInterface
{

    /*public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }*/

    public function getEntityClass(): string
    {
        return DocXEntity::class;
    }

    /*private function scanZipDir($zip, string $directory, $fileList) {
        $props = [];
        foreach ($fileList as $file) {
            $isMatch = fnmatch($directory . '/*', $file);
            if($isMatch) {
                $name = str_replace($directory . '/', '', $file);
                $props[$name] = $zip->readFile($file);
            }
        }
        return $props;
    }*/

    private function saveFiles($zip, string $dir, $files)
    {
        foreach ($files as $file => $content) {
            $zip->writeFile($dir . '/' . $file, $content);
        }
    }

    public function findOneByFileName(string $fileName): DocXEntity
    {
        $zip = new Zip($fileName);
        $fileList = $zip->files();
        $docXEntity = new DocXEntity();
        $docXEntity->setFileName($fileName);
        $docXEntity->setProps($zip->getDirectoryFiles(AttributeEnum::PROPS));
        $docXEntity->setRels($zip->getDirectoryFiles(AttributeEnum::RELS));
        $docXEntity->setWord($zip->getDirectoryFiles(AttributeEnum::WORD));
        $docXEntity->setTypes($zip->readFile(AttributeEnum::TYPES));
        $zip->close();
        return $docXEntity;
    }

    public function createByFileName(string $fileName, DocXEntity $docXEntity)
    {
        $zip = new Zip($fileName);
        $this->saveFiles($zip, AttributeEnum::PROPS, $docXEntity->getProps());
        $this->saveFiles($zip, AttributeEnum::RELS, $docXEntity->getRels());
        $this->saveFiles($zip, AttributeEnum::WORD, $docXEntity->getWord());
        $zip->writeFile(AttributeEnum::TYPES, $docXEntity->getTypes());
        $zip->close();
    }

    public function renderEntity(DocXEntity $docXEntity, array $params = []): void
    {
        $word = $docXEntity->getWord();
        $word['document.xml'] = TemplateHelper::render($word['document.xml'], $params, '{{', '}}');
        $docXEntity->setWord($word);
    }

    public function render(string $temlateFile, array $params = []): DocXEntity
    {
        $docXEntity = $this->findOneByFileName($temlateFile);
        $this->renderEntity($docXEntity, $params);
        return $docXEntity;
    }

    public function renderToFile(string $temlateFile, $resultFile, array $params = []): void
    {
        $docXEntity = $this->render($temlateFile, $params);
        $this->createByFileName($resultFile, $docXEntity);
    }
}

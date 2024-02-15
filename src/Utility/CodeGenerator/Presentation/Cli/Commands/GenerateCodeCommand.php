<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Lib\Components\Byte\Helpers\ByteSizeFormatHelper;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;

class GenerateCodeCommand extends Command
{

    public function __construct(
        string $name = null,
        private CommandBusInterface $bus,
        private array $interacts
    )
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->addOption(
            'inputFile',
            null,
            InputOption::VALUE_OPTIONAL,
            'File with input data'
        );
    }

    protected function input(SymfonyStyle $io): array
    {
        $commands = [];
        foreach ($this->interacts as $interact) {
            /** @var InteractInterface $interact */
            $interactCommands = $interact->input($io);
            if ($interactCommands) {
                $commands = ArrayHelper::merge($commands, $interactCommands);
            }
        }
        return $commands;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFile = $input->getOption('inputFile');
        if ($inputFile) {
            $renderParams = ['directory' => $_SERVER['OLDPWD']];
            $inputFile = TemplateHelper::render($inputFile, $renderParams, '{{', '}}');
            $store = new StoreFile($inputFile);
            $commands = $store->load();
        } else {
            $commands = $this->input($io);
        }
        if ($commands) {
            try {
                $collection = $this->handleCommands($commands);
            } catch (UnprocessableEntityException $exception) {
                $errors = [];
                foreach ($exception->getViolations() as $violation) {
                    $fieldName = $violation->getPropertyPath();
                    $error = "$fieldName: {$violation->getMessage()}";
                    $errors[] = $error;
                }
                throw new \Exception('Unprocessable entity.' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $errors));
            }
            $this->outputGeneratedFiles($collection, $io);
            $this->outputInfoTable($collection, $io);
            $io->success('Code generated successfully');
        }
        return Command::SUCCESS;
    }

    protected function handleCommands(array $commands): GenerateResultCollection
    {
//        $collection = new GenerateResultCollection();
        foreach ($commands as $command) {
            /*$collection = */$this->bus->handle($command);
//            $collection->merge($resultCollection);
        }
        $collection = ContainerHelper::getContainer()->get(GenerateResultCollection::class);
        GeneratorHelper::dump($collection);
        $items = [];
        foreach ($collection->getAll() as $result) {
            $items[$result->getName()] = $result;
        }
        ksort($items);
        return new GenerateResultCollection($items);
    }

    private function outputGeneratedFiles(GenerateResultCollection $collection, SymfonyStyle $io): void
    {
        $io->title('Generated files');
        $generatedFiles = $this->getFilesList($collection);
        $io->listing($generatedFiles);
    }

    private function outputInfoTable(GenerateResultCollection $collection, SymfonyStyle $io): void
    {
        $io->title('Info');
        $table = $this->generateInfoTableRows($collection);
        $size = $this->calculateSize($collection);
        $generatedFiles = $this->getFilesList($collection);
        $table[] = ['Total files', count($generatedFiles)];
        $table[] = ['Total size', ByteSizeFormatHelper::sizeFormat($size)];
        $io->table([], $table);
    }

    private function generateInfoTableRows(GenerateResultCollection $collection): array
    {
        $table = [];
        foreach ($collection->getAll() as $result) {
            if ($result instanceof InfoResult) {
                $table[] = [$result->getName(), $result->getContent()];
            }
        }
        return $table;
    }

    private function calculateSize(GenerateResultCollection $collection): int
    {
        $size = 0;
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult) {
                $size = $size + mb_strlen($result->getContent());
            }
        }
        return $size;
    }

    private function getFilesList(GenerateResultCollection $collection): array
    {
        $list = [];
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult) {
                $file = GeneratorFileHelper::fileNameTotoRelative(realpath($result->getName()));
                $prefix = $result->isNew() ? '<bg=green>ADD</>' : '<bg=blue>UPD</>';
                $list[] = $prefix . ' ' . $file;
            }
        }
        return $list;
    }
}

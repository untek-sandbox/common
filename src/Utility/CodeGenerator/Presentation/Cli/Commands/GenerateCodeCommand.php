<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

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
        if($inputFile) {
            $inputFile = TemplateHelper::render($inputFile, [
                'directory' => $_SERVER['OLDPWD'],
            ], '{{', '}}');
            $store = new StoreFile($inputFile);
            $commands = $store->load();
            $io->info('Loaded from config file.');
        } else {
            $commands = $this->input($io);
        }

        if ($commands) {
            try {
                $files = $this->handleCommands($commands);
            } catch (UnprocessableEntityException $exception) {
                $errors = [];
                foreach ($exception->getViolations() as $violation) {
                    $fieldName = $violation->getPropertyPath();
                    $error = "$fieldName: {$violation->getMessage()}";
                    $errors[] = $error;
                }
                throw new \Exception('Unprocessable entity.' . PHP_EOL . PHP_EOL .implode(PHP_EOL, $errors));
            }

            $io->newLine();
            $io->writeln('Generated files:');
            $io->listing($files);
            $io->success('Code generated successfully');
        }
        return Command::SUCCESS;
    }

    protected function handleCommands(array $commands): array
    {
        $result = [];
        foreach ($commands as $command) {
            $handleResult = $this->bus->handle($command);
            if ($handleResult) {
                $result = ArrayHelper::merge($result, $handleResult);
            }
        }
        return $result;
    }
}

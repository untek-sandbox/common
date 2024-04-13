<?php

namespace Untek\Utility\DuplicateFinder\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\DuplicateFinder\Domain\Model\FileDto;

class FindCommand extends Command
{

    public static function getDefaultName(): string
    {
        return 'dublicate-finder:find';
    }

    protected function configure()
    {
        $this->addOption(
            'directory',
            null,
            InputOption::VALUE_REQUIRED,
            ''
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Example run console command');

        $directory = $input->getOption('directory');

        /** @var \Symfony\Component\Finder\SplFileInfo[] $list */
        $list = (new Finder())->files()->in($directory);

        $io->progressStart(count($list));

        $collection = [];
        foreach ($list as $item) {
            $fileDto = new FileDto();
            $fileDto->setFilename($item->getPathname());
            $hash = hash_file('sha256', $item->getPathname());
            $fileDto->setHash($hash);
            $collection[] = $fileDto;
            $io->progressAdvance();
        }
        $io->progressFinish();

        $rr = [];
        foreach ($collection as $fileDto) {
            $hash = $fileDto->getHash();
            $rr[$hash][] = $fileDto->getFilename();
        }
        foreach ($rr as $index => $lisst) {
            if(count($lisst) > 1) {
                $io->title($index);
                foreach ($lisst as $item) {
                    $io->writeln($item);
                }
            }
        }

        $io->success('Load complete!');

        return Command::SUCCESS;
    }
}

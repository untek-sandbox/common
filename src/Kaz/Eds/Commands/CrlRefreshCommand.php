<?php

namespace Untek\Kaz\Eds\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Kaz\Eds\Domain\Entities\HostEntity;
use Untek\Kaz\Eds\Domain\Entities\LogEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Services\CrlServiceInterface;
use Untek\Kaz\Eds\Domain\Interfaces\Services\HostServiceInterface;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;

class CrlRefreshCommand extends Command
{

    protected static $defaultName = 'eds:crl:refresh';
    private $crlService;
    private $hostService;

    public function __construct(CrlServiceInterface $crlService, HostServiceInterface $hostService)
    {
        parent::__construct(self::$defaultName);
        $this->crlService = $crlService;
        $this->hostService = $hostService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># eds:crl:refresh</>');

        /** @var HostEntity[] $hostCollecttion */
        $hostCollecttion = $this->hostService->findAll();

        $titles = CollectionHelper::getColumn($hostCollecttion, 'title');
        $hostCollecttionIndexed = CollectionHelper::indexing($hostCollecttion, 'title');

        $output->writeln('');
        $question = new ChoiceQuestion(
            'Select host',
            $titles
        );
        $selected = $this->getHelper('question')->ask($input, $output, $question);

        /** @var HostEntity $hostEntity */
        $hostEntity = $hostCollecttionIndexed[$selected];

        $count = $this->crlService->refreshCountByHostId($hostEntity->getId());
        $output->writeln(['', '<fg=blue>Count: ' . $count . '</>', '']);

        /** @var LogEntity $logEntity */
        $logEntity = $this->crlService->refreshByHostId($hostEntity->getId());
        
        $output->writeln(['', '<fg=green>Refresh success!</>', '']);
        $output->writeln(['', '<fg=green>Created: ' . $logEntity->getCreatedCount() . '</>', '']);

        return 0;
    }
}

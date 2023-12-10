<?php

namespace Untek\Framework\Telegram\Symfony4\Commands;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\LockFactory;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Core\Container\Traits\ContainerAwareTrait;
use Untek\Framework\Console\Symfony4\Traits\IOTrait;
use Untek\Framework\Console\Symfony4\Traits\LockTrait;
use Untek\Framework\Console\Symfony4\Traits\LoopTrait;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Services\LongPullService;

class LongPullCommand extends Command
{

    use ContainerAwareTrait;
    use LockTrait;
    use LoopTrait;
    use IOTrait;

    protected static $defaultName = 'telegram:long-pull';
    protected $longPullService;
    protected $configRepository;

    public function __construct(
        LongPullService $longPullService,
        ConfigRepository $configRepository,
        LockFactory $lockFactory,
        ContainerInterface $container
    )
    {
        parent::__construct(self::$defaultName);
        $this->longPullService = $longPullService;
        $this->configRepository = $configRepository;
        $this->setLockFactory($lockFactory);
        $this->setContainer($container);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setInputOutput($input, $output);
        $output->writeln('<fg=white># Long pull</>');
        $output->writeln('');

        $output->writeln('<fg=white>timeout:</> <fg=yellow>' . $this->configRepository->getLongpullTimeout() . ' second</>');
        $name = 'telegramBot.longPull';
//        $this->runProcessWithLock($name);

        try {
            $this->runProcessWithLock($name);
        } catch (LockAcquiringException $e) {
            $output->writeln('<fg=yellow>' . $e->getMessage() . '</>');
            $output->writeln('');
        }

        return Command::SUCCESS;
    }

    protected function runLoopItem(): void
    {
        $output = $this->getOutput();
        if (EnvHelper::isDebug()) {
            $output->writeln('<fg=white>wait...</>');
        }
        $updates = $this->longPullService->findAll();
        if ($updates) {
            //$output->writeln('<fg=green>has updates</>');
            foreach ($updates as $update) {
//                    dd($update['message']['from']['id']);
                if (!empty($update['message'])) {
                    $line = 'message ' . $update['update_id'] . ' from ' . $update['message']['chat']['id'];
                    if (isset($update['message']['chat']['username'])) {
                        $line .= ' (@' . $update['message']['chat']['username'] . ')';
                    } elseif ($update['message']['chat']['first_name']) {
                        $line .= ' (' . $update['message']['chat']['first_name'] . ')';
                    }
                    $output->write('<fg=default> ' . $line . ' ... </>');
                    try {
                        $this->longPullService->runBotFromService($update);
                        $output->writeln('<fg=green>OK</>');
                    } catch (\Throwable $e) {
                        $this->longPullService->setHandled($update);
                        $output->writeln('<fg=red>FAIL ' . $e->getMessage() . '</>');
                    }
                } elseif (!empty($update['channel_post'])) {
                    $line = 'channel post ' . $update['update_id'] . ' from ' . $update['channel_post']['chat']['id'];
                    if ($update['channel_post']['chat']['title']) {
                        $line .= ' (' . $update['channel_post']['chat']['title'] . ')';
                    }
                    $output->write('<fg=default> ' . $line . ' ... </>');
                    $output->writeln('<fg=yellow>SKIP</>');
                    $this->longPullService->setHandled($update);
                }
            }
        }
    }
}

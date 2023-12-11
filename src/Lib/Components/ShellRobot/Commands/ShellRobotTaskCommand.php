<?php

namespace Untek\Lib\Components\ShellRobot\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Lib\Components\ShellRobot\Domain\Services\ProfileService;
use Untek\Lib\Components\ShellRobot\Domain\Services\TaskService;
use Untek\Framework\Console\Domain\Libs\IO;
use Untek\Sandbox\Sandbox\Deployer\Domain\Libs\ConfigureServerDeployShell;

class ShellRobotTaskCommand extends Command
{

    protected static $defaultName = 'shell-robot:task:run';

    /** @var IO */
    private $io;

    /** @var TaskService */
    private $taskService;

    /** @var ProfileService */
    private $profileService;

    public function __construct(TaskService $taskService, ProfileService $profileService)
    {
        parent::__construct(self::$defaultName);
        $this->taskService = $taskService;
        $this->profileService = $profileService;
    }

    protected function configure()
    {
        $this->addArgument('projectName', InputArgument::OPTIONAL);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        set_time_limit(0);
        $this->io = new IO($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<fg=white># Deployer. Deploy</>']);

        $profileNames = $this->getProfileNames();
        $this->taskService->runTasks($profileNames, $this->io);
        /*foreach ($profileNames as $profileName) {
            $this->taskService->run($profileName, $this->io);
        }*/
        $this->io->success('Success!');

        return Command::SUCCESS;
    }

    private function getProfileNames(): array
    {
        $projectName = $this->io->getInput()->getArgument('projectName');
        if (empty($projectName)) {
            $profileCollection = $this->profileService->findAll();
            $profiles = ArrayHelper::getColumn($profileCollection->toArray(), 'title');
            $projectNames = $this->io->multiChoiceQuestion('Select profiles', $profiles);
        } else {
            $projectNames = [
                $projectName
            ];
        }
        return $projectNames;
    }
}

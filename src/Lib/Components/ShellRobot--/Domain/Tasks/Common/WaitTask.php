<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\Common;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;

class WaitTask extends BaseShell implements TaskInterface
{

    public $seconds = null;
    protected $title = 'Wait {{seconds}} sec.';

    public function run()
    {
        if(empty($this->seconds)) {
            throw new \Exception('');
        }
        $seconds = $this->seconds;
        $this->io->write('   ');
        while ($seconds >= 0) {
            $this->io->write('.');
            sleep(1);
            $seconds--;
        }
        $this->io->writeln('');
    }
}

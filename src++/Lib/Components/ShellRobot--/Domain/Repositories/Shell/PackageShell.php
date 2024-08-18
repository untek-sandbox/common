<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Text\Helpers\StringHelper;
use Untek\Core\Text\Helpers\TextHelper;
use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShellDriver;

class PackageShell extends BaseShellDriver
{

    public function addRepository($package, $options = [])
    {
        return $this->runCommand("sudo add-apt-repository -y $package");
    }

    public function install($package, $options = [])
    {
        if ($this->isInstalled($package)) {
            echo "  $package alredy exist\n";
            return false;
        } else {
            $result = $this->runCommand("sudo apt-get install $package -y");
            echo "  $package installed\n";
            return $result ?: true;
        }
    }

    public function installBatch(array $packages)
    {
        $exists = $new = [];
        foreach ($packages as $package) {
            if ($this->isInstalled($package)) {
                $exists[] = $package;
            } else {
                $new[] = $package;
            }
        }
        foreach ($exists as $package) {
            echo "  $package alredy exist\n";
        }
        $packagesString = implode(' ', $new);
        if (trim($packagesString) != '') {
            $this->install($packagesString);
            foreach ($new as $package) {
                echo "  $package installed\n";
            }
        }
    }

    public function update()
    {
        return $this->runCommand('sudo apt-get update -y');
    }

    public function isInstalled(string $package): bool
    {
        $list = $this->find($package);
        $arr = [];
        foreach ($list as $line) {
            $line = TextHelper::removeDoubleSpace($line);
            $data = explode(' ', $line);
            $item = [
                'name' => $data[1],
                'version' => $data[2],
                'platform' => $data[3],
                'description' => $data[4],
            ];
            if($item['name'] == $package) {
                $arr[] = $item;
            }
        }
        return !empty($arr);
    }

    protected function find(string $package)
    {

        try {
            $result = $this->runCommand("dpkg-query --list | grep -i $package");
        } catch (\Throwable $e) {
            return [];
        }
        $result = trim($result);
        $list = explode(PHP_EOL, $result);
        return $list;
    }
}

<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Services;

use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Config\ProfileRepository;

class ProfileService
{

    private $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function findAll()
    {
        return $this->profileRepository->findAll();
    }

    public function findOneCurrent()
    {
        $profileName = ShellFactory::getVarProcessor()->get('currentProfile');
        return $this->profileRepository->findOneByName($profileName);
    }

    public function findOneByName(string $profileName)
    {
        return $this->profileRepository->findOneByName($profileName);
    }
}

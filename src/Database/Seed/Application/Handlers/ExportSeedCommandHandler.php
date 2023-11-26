<?php

namespace Untek\Database\Seed\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class ExportSeedCommandHandler
{

    /**
     * @param \Untek\Database\Seed\Application\Commands\ExportSeedCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\Untek\Database\Seed\Application\Commands\ExportSeedCommand $command)
    {
        $validator = new \Untek\Database\Seed\Application\Validators\ExportSeedCommandValidator();
        $validator->validate($command);

//        dd($command);
        
        // TODO: Implement logic
    }
}
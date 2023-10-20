<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    /**
     * @param \<?= $commandClassName ?> $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $command)
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($command);

        // TODO: Implement logic
    }
}
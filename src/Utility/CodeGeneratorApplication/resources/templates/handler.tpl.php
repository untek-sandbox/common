<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 * @var string $modelName
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;

class <?= $className ?>

{

    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $command)
    {
        $validator = new \<?= $validatorClassName ?>($this->translator);
        $validator->validate($command);

        // TODO: Implement logic

        return [
            'todo' => 'implement this logic'
        ];
    }
}
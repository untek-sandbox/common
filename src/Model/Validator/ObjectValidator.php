<?php

namespace Untek\Model\Validator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class ObjectValidator
{

    public function __construct(
        private ValidationRulesExtractor $extractor,
        private NormalizerInterface|DenormalizerInterface $normalizer,
    )
    {
    }

    public function validate(object|string $object, $data): ConstraintViolationListInterface
    {
        if (is_string($object)) {
            $type = $object;
            $object = $this->normalizer->denormalize($data, $object);
        } else {
            $type = get_class($object);
        }
        $rules = $this->extractor->extractRuels($type);
        $rules = new Assert\Collection([
            'fields' => $rules
        ], null, $object);
        $validator = Validation::createValidator();
        return $validator->validate($data, $rules);
    }
}
<?php

namespace Untek\Model\Validator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ObjectValidator
{

    public function __construct(
        private ValidationRulesExtractor $extractor,
        private NormalizerInterface|DenormalizerInterface $normalizer,
        private ?TranslatorInterface $translator = null,
    )
    {
    }

    public function validate(object|string $object, array $data = null): ConstraintViolationListInterface
    {
        if (is_string($object)) {
            $type = $object;
            $object = $this->normalizer->denormalize($data, $object);
        } else {
            $type = get_class($object);
            if ($data === null) {
                $data = $this->normalizer->normalize($object);
            }
        }
        $rules = $this->extractor->extractRuels($type);
        $rules = new Assert\Collection([
            'fields' => $rules
        ], payload: $object);
        $validator = $this->createValidator();
        return $validator->validate($data, $rules);
    }

    private function createValidator(): ValidatorInterface
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        if ($this->translator) {
            $validatorBuilder->setTranslator($this->translator);
        }
        $validatorBuilder->setTranslationDomain('validators');
        return $validatorBuilder->getValidator();
    }
}
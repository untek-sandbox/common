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
        private ValidationConstraintExtractor $extractor,
        private NormalizerInterface|DenormalizerInterface $normalizer,
        private ?TranslatorInterface $translator = null,
        private string $translationDomain = 'validators',
    )
    {
    }

    public function validate(object|string $object, array $data = null): ConstraintViolationListInterface
    {
        if (is_string($object)) {
            $type = $object;
//            $object = $this->normalizer->denormalize($data, $object);
            $payload = $data;
        } else {
            $type = get_class($object);
            if ($data === null) {
                $data = $this->normalizer->normalize($object);
                /*$caseNameConverter = new CamelCaseToSnakeCaseNameConverter();
                dd($data);
                foreach ($data as $name => $value) {
                    $name = $caseNameConverter->denormalize($name);
                    dump($name);
                }*/

            }
            $payload = $object;
        }
        $constraints = $this->extractor->extract($type);
        if($constraints) {
            $constraints = new Assert\Collection([
                'fields' => $constraints
            ], payload: $payload);
        } else {
            $constraints = new Assert\Collection([], payload: $payload);
        }

        $validator = $this->createValidator();
        return $validator->validate($data, $constraints);
    }

    private function createValidator(): ValidatorInterface
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        if ($this->translator) {
            $validatorBuilder->setTranslator($this->translator);
            $validatorBuilder->setTranslationDomain($this->translationDomain);
        }
        return $validatorBuilder->getValidator();
    }
}
<?php

namespace Untek\Component\Collection;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\Hydrator\HydratorAwareInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class CollectionNormalizer implements NormalizerInterface, DenormalizerInterface, HydratorAwareInterface
{

    private NormalizerInterface|DenormalizerInterface $hydrator;

    public function setHydrator(NormalizerInterface|DenormalizerInterface $hydrator): void
    {
        $this->hydrator = $hydrator;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $list = [];
        foreach ($data as $item) {
            $list[] = $this->hydrator->denormalize($item, $type::getClass());
        }
        return new $type($list);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        if (!class_exists($type)) {
            return false;
        }
        $reflection = new \ReflectionClass($type);
        return array_key_exists(Collection::class, $reflection->getInterfaces());
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var Collection $object */
        return $object->toArray();
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Collection;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method array getSupportedTypes(?string $format)
    }
}
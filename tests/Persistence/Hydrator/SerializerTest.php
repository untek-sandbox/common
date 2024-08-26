<?php

namespace Untek\Tests\Persistence\Hydrator;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\Post;

class SerializerTest extends TestCase
{

    public function testSerializer()
    {
        $this->markTestSkipped();

        $entity1 = new Post('Title 1');

        $serializer = $this->getSerializer();
        $normalizedData = $serializer->normalize($entity1);

        $this->assertEquals($entity1->getId(), $normalizedData['id']);
        $this->assertEquals($entity1->getTitle(), $normalizedData['title']);
        $this->assertEquals($entity1->getCreatedAt()->format(DateTimeInterface::ATOM), $normalizedData['createdAt']);

        /** @var Post $denormalizedEntity */
        $denormalizedEntity = $serializer->denormalize($normalizedData, Post::class);

        $this->assertEquals($entity1->getCreatedAt(), $denormalizedEntity->getCreatedAt());
        $this->assertEquals($entity1->getId(), $denormalizedEntity->getId());
        $this->assertEquals($entity1->getTitle(), $denormalizedEntity->getTitle());
    }

    protected function getSerializer(): SerializerInterface
    {
        $normalizers = [
            new ArrayDenormalizer(),
            new PropertyNormalizer(),
            new BackedEnumNormalizer(),
            new DateTimeNormalizer(),
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers);
    }
}

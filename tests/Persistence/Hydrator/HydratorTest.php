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
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\Hydrator\Hydrator;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\Comment;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\CommentCollection;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\Post;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\ValueObject1;

class HydratorTest extends TestCase
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

    public function testHydrator()
    {
        $entity1 = new Post(
            'Title 1',
            ['php', 'js'],
            new ValueObject1('qwerty123'),
            new CommentCollection([
                new Comment('Comment 1'),
            ])
        );

        $hydrator = new Hydrator([
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
        ]);

        $dehydratedData = $hydrator->dehydrate($entity1);

        $this->assertEquals($entity1->getId(), $dehydratedData['id']);
        $this->assertEquals($entity1->getTitle(), $dehydratedData['title']);
        $this->assertEquals($entity1->getTags(), $dehydratedData['tags']);
        $this->assertEquals($entity1->getCreatedAt()->format(DateTimeInterface::ATOM), $dehydratedData['createdAt']);
        $this->assertEquals($entity1->getValueObject1()->get(), $dehydratedData['valueObject1']);
        $this->assertEquals($entity1->getComments()->get(0)->getId(), $dehydratedData['comments'][0]['id']);
        $this->assertEquals($entity1->getComments()->get(0)->getContent(), $dehydratedData['comments'][0]['content']);
        $this->assertEquals($entity1->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $dehydratedData['comments'][0]['createdAt']);


        /** @var Post $hydratedEntity1 */
        $hydratedEntity1 = $hydrator->hydrate(Post::class, $dehydratedData);

        $this->assertEquals($entity1->getCreatedAt()->getTimestamp(), $hydratedEntity1->getCreatedAt()->getTimestamp());
        $this->assertEquals($entity1->getId(), $hydratedEntity1->getId());
        $this->assertEquals($entity1->getTags(), $hydratedEntity1->getTags());
        $this->assertEquals($entity1->getValueObject1()->get(), $hydratedEntity1->getValueObject1()->get());
        $this->assertEquals($entity1->getComments()->get(0)->getId(), $hydratedEntity1->getComments()->get(0)->getId());
        $this->assertEquals($entity1->getComments()->get(0)->getContent(), $hydratedEntity1->getComments()->get(0)->getContent());
        $this->assertEquals($entity1->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $hydratedEntity1->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM));
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

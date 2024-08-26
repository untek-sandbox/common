<?php

namespace Untek\Tests\Persistence\Hydrator;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\Comment;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\CommentCollection;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\Post;
use Untek\Tests\Persistence\Hydrator\Fixture\Model\ValueObject1;

class HydratorTest extends TestCase
{

    public function testHydrate()
    {
        $data = [
            "id" => "01J67QKTNVWQ73HB6J0YZ108RQ",
            "title" => "Title 1",
            "tags" => [
                "php",
                "js",
            ],
            "createdAt" => "2024-08-26T16:16:57+00:00",
            "valueObject1" => "qwerty123",
            "comments" => [
                [
                    "id" => "01J67QKTNTFTZPH9ZV66HWTFXB",
                    "content" => "Comment 1",
                    "createdAt" => "2024-08-26T16:16:57+00:00",
                ]
            ]
        ];
        /** @var Post $hydratedEntity1 */
        $post = $this->getHydrator()->denormalize($data, Post::class);

        $this->assertEquals($post->getId(), $data['id']);
        $this->assertEquals($post->getTitle(), $data['title']);
        $this->assertEquals($post->getTags(), $data['tags']);
        $this->assertEquals($post->getCreatedAt()->format(DateTimeInterface::ATOM), $data['createdAt']);
        $this->assertEquals($post->getValueObject1()->get(), $data['valueObject1']);
        $this->assertEquals($post->getComments()->get(0)->getId(), $data['comments'][0]['id']);
        $this->assertEquals($post->getComments()->get(0)->getContent(), $data['comments'][0]['content']);
        $this->assertEquals($post->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $data['comments'][0]['createdAt']);
    }

    public function testDehydrate()
    {
        $sourcePost = new Post(
            'Title 1',
            ['php', 'js'],
            new ValueObject1('qwerty123'),
            new CommentCollection([
                new Comment('Comment 1'),
            ])
        );

        $dehydratedData = $this->getHydrator()->normalize($sourcePost);

        $this->assertEquals($sourcePost->getId(), $dehydratedData['id']);
        $this->assertEquals($sourcePost->getTitle(), $dehydratedData['title']);
        $this->assertEquals($sourcePost->getTags(), $dehydratedData['tags']);
        $this->assertEquals($sourcePost->getCreatedAt()->format(DateTimeInterface::ATOM), $dehydratedData['createdAt']);
        $this->assertEquals($sourcePost->getValueObject1()->get(), $dehydratedData['valueObject1']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getId(), $dehydratedData['comments'][0]['id']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getContent(), $dehydratedData['comments'][0]['content']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $dehydratedData['comments'][0]['createdAt']);

        /** @var Post $hydratedPost */
        $hydratedPost = $this->getHydrator()->denormalize($dehydratedData, Post::class);

        $this->assertEquals($sourcePost->getCreatedAt()->getTimestamp(), $hydratedPost->getCreatedAt()->getTimestamp());
        $this->assertEquals($sourcePost->getId(), $hydratedPost->getId());
        $this->assertEquals($sourcePost->getTags(), $hydratedPost->getTags());
        $this->assertEquals($sourcePost->getValueObject1()->get(), $hydratedPost->getValueObject1()->get());
        $this->assertEquals($sourcePost->getComments()->get(0)->getId(), $hydratedPost->getComments()->get(0)->getId());
        $this->assertEquals($sourcePost->getComments()->get(0)->getContent(), $hydratedPost->getComments()->get(0)->getContent());
        $this->assertEquals($sourcePost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $hydratedPost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM));
    }

    private function getHydrator(): NormalizerInterface|DenormalizerInterface
    {
        return new ObjectNormalizer([
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
        ]);
    }
}

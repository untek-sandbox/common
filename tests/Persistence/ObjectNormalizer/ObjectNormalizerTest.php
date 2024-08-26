<?php

namespace Untek\Tests\Persistence\ObjectNormalizer;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\Uid\UidNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Comment;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\CommentCollection;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Post;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\ValueObject1;

class ObjectNormalizerTest extends TestCase
{

    public function testDenormalize()
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
        /** @var Post $post */
        $post = $this->getObjectNormalizer()->denormalize($data, Post::class);

        $this->assertEquals($post->getId(), $data['id']);
        $this->assertEquals($post->getTitle(), $data['title']);
        $this->assertEquals($post->getTags(), $data['tags']);
        $this->assertEquals($post->getCreatedAt()->format(DateTimeInterface::ATOM), $data['createdAt']);
        $this->assertEquals($post->getValueObject1()->get(), $data['valueObject1']);
        $this->assertEquals($post->getComments()->get(0)->getId(), $data['comments'][0]['id']);
        $this->assertEquals($post->getComments()->get(0)->getContent(), $data['comments'][0]['content']);
        $this->assertEquals($post->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $data['comments'][0]['createdAt']);
    }

    public function testNormalize()
    {
        $sourcePost = new Post(
            'Title 1',
            ['php', 'js'],
            new ValueObject1('qwerty123'),
            new CommentCollection([
                new Comment('Comment 1'),
            ])
        );

        $normalizedData = $this->getObjectNormalizer()->normalize($sourcePost);

        $this->assertEquals($sourcePost->getId()->toBase58(), $normalizedData['id']);
        $this->assertEquals($sourcePost->getTitle(), $normalizedData['title']);
        $this->assertEquals($sourcePost->getTags(), $normalizedData['tags']);
        $this->assertEquals($sourcePost->getCreatedAt()->format(DateTimeInterface::ATOM), $normalizedData['createdAt']);
        $this->assertEquals($sourcePost->getValueObject1()->get(), $normalizedData['valueObject1']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getId()->toBase58(), $normalizedData['comments'][0]['id']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getContent(), $normalizedData['comments'][0]['content']);
        $this->assertEquals($sourcePost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $normalizedData['comments'][0]['createdAt']);

        /** @var Post $denormalizedPost */
        $denormalizedPost = $this->getObjectNormalizer()->denormalize($normalizedData, Post::class);

        $this->assertEquals($sourcePost->getCreatedAt()->getTimestamp(), $denormalizedPost->getCreatedAt()->getTimestamp());
        $this->assertEquals($sourcePost->getId(), $denormalizedPost->getId());
        $this->assertEquals($sourcePost->getTags(), $denormalizedPost->getTags());
        $this->assertEquals($sourcePost->getValueObject1()->get(), $denormalizedPost->getValueObject1()->get());
        $this->assertEquals($sourcePost->getComments()->get(0)->getId(), $denormalizedPost->getComments()->get(0)->getId());
        $this->assertEquals($sourcePost->getComments()->get(0)->getContent(), $denormalizedPost->getComments()->get(0)->getContent());
        $this->assertEquals($sourcePost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM), $denormalizedPost->getComments()->get(0)->getCreatedAt()->format(DateTimeInterface::ATOM));
    }

    private function getObjectNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new ObjectNormalizer([
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer(),
        ]);
    }
}

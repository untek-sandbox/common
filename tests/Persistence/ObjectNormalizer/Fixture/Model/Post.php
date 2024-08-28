<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;
use Untek\Component\ObjectNormalizer\Attributes\TypedArray;

class Post
{

    private Ulid $id;
    private string $title;
    private array $tags;
    private DateTimeInterface $createdAt;
    private ?ValueObject1 $valueObject1;

    #[TypedArray(Comment::class)]
    private ?array $comments;

    public function __construct(
        string $title,
        array $tags = [],
        ?ValueObject1 $valueObject1 = null,
        array $comments = null,
    )
    {
        $this->id = new Ulid();
        $this->title = $title;
        $this->createdAt = new DateTimeImmutable();
        $this->tags = $tags;
        $this->valueObject1 = $valueObject1;
        $this->comments = $comments;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getValueObject1(): ?ValueObject1
    {
        return $this->valueObject1;
    }

    public function getComments(): ?array
    {
        return $this->comments;
    }
}
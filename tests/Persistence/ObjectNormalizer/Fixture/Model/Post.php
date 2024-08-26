<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

class Post
{

    private Ulid $id;
    private string $title;
    private array $tags;
    private DateTimeInterface $createdAt;
    private ?ValueObject1 $valueObject1;
    private ?CommentCollection $comments;

    public function __construct(
        string $title,
        array $tags = [],
        ?ValueObject1 $valueObject1 = null,
        ?CommentCollection $comments = null,
    )
    {
        $createdAt = new DateTimeImmutable();
        $this->id = new Ulid();
        $this->title = $title;
        $this->createdAt = $createdAt;
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

    public function getComments(): ?CommentCollection
    {
        return $this->comments;
    }
}
<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;

class Comment
{

    private Ulid $id;
    private string $content;
    private DateTimeInterface $createdAt;

    public function __construct(string $content)
    {
        $this->id = new Ulid();
        $this->content = $content;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;

class Comment
{

    private string $id;
    private string $content;
    private DateTimeInterface $createdAt;

    public function __construct(string $content)
    {
        $createdAt = new DateTimeImmutable();
        $this->id = Ulid::generate($createdAt);
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
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
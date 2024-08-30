<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Symfony\Component\Uid\Ulid;
use DateTimeInterface;

class Author2
{

    private Ulid $id;
    private string $name;
    private ObjectInner $inner;
    private DateTimeInterface $createdAt;
    private CommentCollection $comments;

    public function __construct(
        Ulid $id,
        string $name,
        DateTimeInterface $createdAt,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    /*public function setId(string $id): void
    {
        $this->id = $id;
    }*/

    public function getName(): string
    {
        return $this->name;
    }

    /*public function setName(string $name): void
    {
        $this->name = $name;
    }*/

    public function getInner(): ObjectInner
    {
        return $this->inner;
    }

    public function setInner(ObjectInner $inner): void
    {
        $this->inner = $inner;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getComments(): CommentCollection
    {
        return $this->comments;
    }

    public function setComments(CommentCollection $comments): void
    {
        $this->comments = $comments;
    }

    /*public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }*/
    
}
<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;
use Untek\Component\ObjectNormalizer\Attributes\TypedArray;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

class Author1
{

    private string $id;
    private string $name;

//    #[Ignore]
    private \DateTimeInterface $createdAt;

//    #[TypedArray(Role::class)]
//    private Collection $roles;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /*public function getRoles(): Collection
    {
        return $this->roles;
    }*/
}
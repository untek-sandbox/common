<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;
use Untek\Component\ObjectNormalizer\Attributes\TypedCollection;

class Author
{

    private Ulid $id;
    private string $name;

    #[TypedCollection(Role::class)]
    /** @var Role[] */
    private Collection $roles;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }
}
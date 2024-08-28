<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class Author
{

    private Ulid $id;
    private string $name;
    
    #[Assert\All([new Assert\Type(Role::class)])]
    private array $roles;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
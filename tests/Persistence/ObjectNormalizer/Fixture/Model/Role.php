<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Symfony\Component\Uid\Ulid;

class Role
{

    private Ulid $id;
    private string $name;

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
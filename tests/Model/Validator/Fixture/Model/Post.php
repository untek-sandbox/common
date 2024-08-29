<?php

namespace Untek\Tests\Model\Validator\Fixture\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{

    #[Assert\Positive()]
    private int|string $id;

    #[Assert\Length(min: 3, max: 10)]
    private string $title;

    private int $status = 100;

    private ?array $tags;
}
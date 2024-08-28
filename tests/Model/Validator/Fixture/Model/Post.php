<?php

namespace Untek\Tests\Model\Validator\Fixture\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{

    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private int|string $id;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 10)]
    private string $title;
}
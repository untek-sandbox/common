<?php

namespace Untek\Tests\Persistence\Collection\Fixture\Model;

use Untek\Component\Collection\AbstractEntityCollection;

class CommentCollection extends AbstractEntityCollection
{

    public static function getClass(): string
    {
        return Comment::class;
    }
}
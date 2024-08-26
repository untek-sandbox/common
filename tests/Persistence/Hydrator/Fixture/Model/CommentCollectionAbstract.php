<?php

namespace Untek\Tests\Persistence\Hydrator\Fixture\Model;

use Untek\Component\Collection\AbstractEntityCollection;

class CommentCollectionAbstract extends AbstractEntityCollection
{

    public static function getClass(): string
    {
        return Comment::class;
    }
}
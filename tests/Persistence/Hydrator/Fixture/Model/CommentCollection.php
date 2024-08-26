<?php

namespace Untek\Tests\Persistence\Hydrator\Fixture\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Untek\Component\Collection\EntityCollectionInterface;

class CommentCollection extends ArrayCollection implements EntityCollectionInterface
{

    public static function getClass(): string
    {
        return Comment::class;
    }
}
<?php

namespace Untek\Tests\Persistence\Collection;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Untek\Tests\Persistence\Collection\Fixture\Model\Comment;
use Untek\Tests\Persistence\Collection\Fixture\Model\CommentCollection;
use Untek\Tests\Persistence\Collection\Fixture\Model\Post;

class EntityCollectionTest extends TestCase
{

    public function testSuccess()
    {
        $entities = [
            new Comment('comment 1'),
        ];
        $collection = new CommentCollection($entities);
        $collection->add(new Comment('comment 2'));
        $collection->set(3, new Comment('comment 3'));
        $this->assertEquals('comment 1', $collection->get(0)->getContent());
        $this->assertEquals('comment 2', $collection->get(1)->getContent());
        $this->assertEquals('comment 3', $collection->get(3)->getContent());

        $this->assertEquals('comment 1', $collection[0]->getContent());
    }

    public function testBadType()
    {
        $entities = [
            new Comment('comment 1'),
            new Post(),
        ];
        $this->expectException(RuntimeException::class);
        $collection = new CommentCollection($entities);
    }

    public function testAddBadType()
    {
        $this->expectException(RuntimeException::class);
        $collection = new CommentCollection();
        $collection->add(new Post());
    }

    public function testSetBadType()
    {
        $this->expectException(RuntimeException::class);
        $collection = new CommentCollection();
        $collection->set(3, new Post());
    }
}

<?php

namespace Untek\Tests\Model\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Untek\Model\Validator\ValidationConstraintExtractor;
use Untek\Tests\Model\Validator\Fixture\Model\EmptyAttribute;
use Untek\Tests\Model\Validator\Fixture\Model\Post;

class ValidationConstraintExtractorTest extends TestCase
{

    public function testExtractByClassName()
    {
        $extrator = new ValidationConstraintExtractor();
        $constraints = $extrator->extract(Post::class);

        $this->assertCount(4, $constraints);

        $this->assertEquals([
            new NotBlank(),
            new AtLeastOneOf([
                new Type('string'),
                new Type('int'),
            ]),
            new Positive(),
        ], $constraints['id']);

        $this->assertEquals([
            new NotBlank(),
            new Type('string'),
            new Length(min: 3, max: 10),
        ], $constraints['title']);


        $this->assertEquals(new Optional([
            new Type('int'),
        ]), $constraints['status']);

        $this->assertEquals(new Optional([
            new Type('array'),
        ]), $constraints['tags']);
    }

    public function testExtractByClassNameEmpty()
    {
        $extrator = new ValidationConstraintExtractor();
        $constraints = $extrator->extract(EmptyAttribute::class);

        $this->assertCount(0, $constraints);
    }
}

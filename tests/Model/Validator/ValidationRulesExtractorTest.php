<?php

namespace Untek\Tests\Model\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Untek\Component\Collection\EntityCollectionNormalizer;
use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Model\Validator\ObjectValidator;
use Untek\Model\Validator\ValidationRulesExtractor;
use Untek\Tests\Model\Validator\Fixture\Model\Post;

class ValidationRulesExtractorTest extends TestCase
{

    public function testHasErrors()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];

        $postData777 = [
            'id' => 123,
            'title' => 'asfddsfs',
        ];

        $errors = $this->getValidator()->validate(Post::class, $postData);

        $this->assertCount(2, $errors);

        $this->assertEquals('This value should be positive.', $errors->get(0)->getMessage());
        $this->assertEquals('[id]', $errors->get(0)->getPropertyPath());

        $this->assertEquals('This value is too long. It should have 10 characters or less.', $errors->get(1)->getMessage());
        $this->assertEquals('[title]', $errors->get(1)->getPropertyPath());
    }

    public function testNotHasErrors()
    {
        $postData = [
            'id' => 123,
            'title' => 'asfddsfs',
        ];

        $errors = $this->getValidator()->validate(Post::class, $postData);

        $this->assertCount(0, $errors);
    }

    private function getValidator(): ObjectValidator
    {
        $extrator = new ValidationRulesExtractor();
        return new ObjectValidator($extrator, $this->getObjectNormalizer());
    }

    private function getObjectNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        $normalizers = [
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new EntityCollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
        ];
        return new ObjectNormalizer($normalizers, new CamelCaseToSnakeCaseNameConverter());
    }
}

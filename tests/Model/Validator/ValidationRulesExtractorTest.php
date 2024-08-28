<?php

namespace Untek\Tests\Model\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
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
        $errors = $this->getValidator()->validate(Post::class, $postData);

        $this->assertCount(2, $errors);

        $this->assertEquals('This value should be positive.', $errors->get(0)->getMessage());
        $this->assertEquals('[id]', $errors->get(0)->getPropertyPath());

        $this->assertEquals('This value is too long. It should have 10 characters or less.', $errors->get(1)->getMessage());
        $this->assertEquals('[title]', $errors->get(1)->getPropertyPath());
    }

    public function testHasErrorsInObject()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $post = $this->getObjectNormalizer()->denormalize($postData, Post::class);
        $errors = $this->getValidator()->validate($post);

        $this->assertCount(2, $errors);

        $this->assertEquals('This value should be positive.', $errors->get(0)->getMessage());
        $this->assertEquals('[id]', $errors->get(0)->getPropertyPath());

        $this->assertEquals('This value is too long. It should have 10 characters or less.', $errors->get(1)->getMessage());
        $this->assertEquals('[title]', $errors->get(1)->getPropertyPath());
    }

    public function testHasErrorsRu()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $errors = $this->getValidator('ru_RU')->validate(Post::class, $postData);

        $this->assertCount(2, $errors);

        $this->assertEquals('Значение должно быть положительным.', $errors->get(0)->getMessage());
        $this->assertEquals('[id]', $errors->get(0)->getPropertyPath());

        $this->assertEquals('Значение слишком длинное. Должно быть равно 10 символам или меньше.', $errors->get(1)->getMessage());
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

    private function getValidator(string $language = 'en_US'): ObjectValidator
    {
        $translator = $this->getTranslator($language);
        $extrator = new ValidationRulesExtractor();
        return new ObjectValidator($extrator, $this->getObjectNormalizer(), $translator);
    }

    private function getTranslator(string $language = 'en_US'): TranslatorInterface
    {
        $translator = new Translator($language);
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', __DIR__ . '/../../../../../symfony/validator/Resources/translations/validators.ru.xlf', 'ru_RU', 'validators');
        return $translator;
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

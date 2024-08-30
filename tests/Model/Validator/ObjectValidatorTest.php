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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Develop\Debug\DataDumper;
use Untek\Model\Validator\ObjectValidator;
use Untek\Model\Validator\ValidationConstraintExtractor;
use Untek\Tests\Model\Validator\Fixture\Model\EmptyAttribute;
use Untek\Tests\Model\Validator\Fixture\Model\Post;

class ObjectValidatorTest extends TestCase
{

    public function testHasErrors()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $errors = $this->getValidator()->validate(Post::class, $postData);

        $this->assertViolations([
            "[id]" => [
                "This value should be positive.",
            ],
            "[title]" => [
                "This value is too long. It should have 10 characters or less.",
            ],
        ], $errors);
    }

    public function testTypeError()
    {
        $postData = [
            'id' => true,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $errors = $this->getValidator()->validate(Post::class, $postData);

        $this->assertViolations([
                '[id]' => [
                    'This value should satisfy at least one of the following constraints: [1] This value should be of type string. [2] This value should be of type int.',
                ],
                '[title]' => [
                    'This value is too long. It should have 10 characters or less.',
                ],
        ], $errors);
    }

    public function testHasErrorsInObject()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $post = $this->getObjectNormalizer()->denormalize($postData, Post::class);
        $errors = $this->getValidator()->validate($post);

        $this->assertViolations([
                '[id]' => [
                    'This value should be positive.',
                ],
                '[title]' => [
                    'This value is too long. It should have 10 characters or less.',
                ],
        ], $errors);
    }

    public function testHasErrorsRu()
    {
        $postData = [
            'id' => -123,
            'title' => 'asfddsfs00000000000000000000000000000',
        ];
        $errors = $this->getValidator('ru_RU')->validate(Post::class, $postData);

        $this->assertViolations([
            '[id]' => [
                'Значение должно быть положительным.',
            ],
            '[title]' => [
                'Значение слишком длинное. Должно быть равно 10 символам или меньше.',
            ],
        ], $errors);
    }

    public function testNotHasErrors()
    {
        $postData = [
            'id' => 123,
            'title' => 'asfddsfs',
            'status' => 100,
            'tags' => ['tag1']
        ];
        $errors = $this->getValidator()->validate(Post::class, $postData);

        //DataDumper::print($this->violationsToArray($errors));
        
        $this->assertCount(0, $errors);
    }

    public function testEmptyAttribute()
    {
        $postData = [];
        $errors = $this->getValidator()->validate(EmptyAttribute::class, $postData);

        //DataDumper::print($this->violationsToArray($errors));

        $this->assertCount(0, $errors);
    }

    private function getValidator(string $language = 'en_US'): ObjectValidator
    {
        $translator = $this->getTranslator($language);
        $extrator = new ValidationConstraintExtractor();
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
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
        ];
        return new ObjectNormalizer($normalizers, new CamelCaseToSnakeCaseNameConverter());
    }

    private function violationsToArray(ConstraintViolationListInterface $errors): array
    {
        $messages = [];
        foreach ($errors as $error) {
            $path = $error->getPropertyPath();
            $messages[$path][] = $error->getMessage();
        }
        return $messages;
    }

    private function assertViolations(array $expected, ConstraintViolationListInterface $errors): void
    {
        $messages = $this->violationsToArray($errors);
        $this->assertEquals($expected, $messages);
    }
}

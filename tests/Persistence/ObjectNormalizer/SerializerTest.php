<?php

namespace Untek\Tests\Persistence\ObjectNormalizer;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Untek\Component\Collection\CollectionNormalizer;
//use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\Collection\TypedEntityCollectionNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Author;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Author1;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Author2;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Comment;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\CommentCollection;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\Post;
use Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\ValueObject1;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class SerializerTest extends TestCase
{

    public function testDenormalize2()
    {
        $serializer = $this->getSerializer2();

        $data = [
            'inner' => [
                'foo' => 'foo',
                'bar' => 'bar'
            ],
            'date' => '1988/01/21'
        ];
        $obj = $serializer->denormalize(
            $data,
            \Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model\ObjectOuter::class
        );

        $this->assertEquals('1988-01-21', $obj->getDate()->format('Y-m-d'));
    }

    public function testDenormalize()
    {
        $data = [
            "id" => "01J67QKTNVWQ73HB6J0YZ108RQ",
            "name" => "User1",
            "createdAt" => "2024-08-26T16:16:57+00:00",
            /*"roles" => [
                [
                    "id" => "01J67QKTNVWQ73HB6J0YZ108RQ",
                    "name" => "Role1",
                ],
            ],*/
        ];

        $data = [
            "id" => "01J67QKTNVWQ73HB6J0YZ108RQ",
            "name" => "User1",
            'inner' => [
                'foo' => 'foo',
                'bar' => 'bar'
            ],
            'created_at' => '2024-08-26T16:16:57+00:00',
            "comments" => [
                [
                    "id" => "01J67QKTNTFTZPH9ZV66HWTFXB",
                    "content" => "Comment 1",
                    "created_at" => "2024-08-26T16:16:57+00:00",
                ]
            ]
        ];

        $serializer = $this->getSerializer2();
        /** @var Author2 $author */
        $author = $serializer->denormalize($data, Author2::class, context: [
//            AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt'],
//            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
        ]);
//        dd($author->getComments()->toArray());

        $this->assertEquals('01J67QKTNVWQ73HB6J0YZ108RQ', $author->getId()->toBase32());
        $this->assertEquals('User1', $author->getName());
        $this->assertEquals('2024-08-26', $author->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals([
            [
                "id" => "01J67QKTNTFTZPH9ZV66HWTFXB",
                "content" => "Comment 1",
                "created_at" => "2024-08-26T16:16:57+00:00",
            ]
        ], $author->getComments()->toArray());

        return;

        try {
            /** @var Author $author */

        } catch (PartialDenormalizationException $e) {
            dd($e);
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException */
            foreach ($e->getErrors() as $exception) {
                $message = sprintf('The type must be one of "%s" ("%s" given).', implode(', ', $exception->getExpectedTypes()), $exception->getCurrentType());
                $parameters = [];
                if ($exception->canUseMessageForUser()) {
                    $parameters['hint'] = $exception->getMessage();
                }
                $violations->add(new ConstraintViolation($message, '', $parameters, null, $exception->getPath(), null));
            };
        }
    }

    protected function getSerializer(): NormalizerInterface|DenormalizerInterface
    {
        $defaultContext = [
            /*AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'id'
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER =>
                function ($articles, $format, $context)  {
                    return $articles->getId();
                }*/
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader(/*new AnnotationReader()*/));
        $propertyTypeExtractor = new ReflectionExtractor();
        $objectNormalizer = new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
            propertyTypeExtractor: $propertyTypeExtractor,
            nameConverter: new CamelCaseToSnakeCaseNameConverter(),
            defaultContext: $defaultContext,
        );
        $normalizers = [
            new PropertyNormalizer(),
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
            new ArrayDenormalizer(),
            $objectNormalizer,
        ];
        return new Serializer($normalizers);
    }

    private function getSerializer2(): NormalizerInterface|DenormalizerInterface
    {
        $defaultContext = [
            /*AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'id'
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER =>
                function ($articles, $format, $context)  {
                    return $articles->getId();
                }*/
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader(/*new AnnotationReader()*/));
        $propertyTypeExtractor = new ReflectionExtractor();
        $objectNormalizer = new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
            propertyTypeExtractor: $propertyTypeExtractor,
            nameConverter: new CamelCaseToSnakeCaseNameConverter(),
            defaultContext: $defaultContext,
        );
        $normalizers = [
//            new PropertyNormalizer(),
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
            new ArrayDenormalizer(),
            $objectNormalizer,
        ];
        return new Serializer($normalizers);
    }

    /*private function getObjectNormalizer(): NormalizerInterface|DenormalizerInterface
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
    }*/
}

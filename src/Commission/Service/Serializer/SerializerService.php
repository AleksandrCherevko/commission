<?php

namespace App\Commission\Service\Serializer;

use App\Commission\Exception\SerializeException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class SerializerService implements SerializerServiceInterface
{
    protected SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new ArrayDenormalizer(),
                new ObjectNormalizer(
                    new ClassMetadataFactory(
                        new AnnotationLoader(
                            new AnnotationReader()
                        )
                    ),
                    null,
                    null,
                    new PropertyInfoExtractor(
                        [],
                        [
                            new PhpDocExtractor(),
                            new ReflectionExtractor(),
                        ]
                    )
                )
            ],
            [
                new JsonEncoder(),
                new XmlEncoder(),
            ]
        );
    }

    /**
     * @template InstanceType
     *
     * @param string                     $data
     * @param class-string<InstanceType> $type
     * @param string                     $format
     *
     * @return InstanceType
     */
    public function deserialize(string $data, string $type, string $format = JsonEncoder::FORMAT): object
    {
        try {
            return $this->serializer->deserialize($data, $type, $format);
        } catch (Throwable $exception) {
            throw new SerializeException('Data parsing error.', ['content' => $data, 'format' => $format]);
        }
    }
}

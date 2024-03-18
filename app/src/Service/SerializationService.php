<?php 

namespace App\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Doctrine\Common\Annotations\AnnotationReader;

class SerializationService
{
    private $data;

    public function serialize(
        $object, 
        Array $groups = ['default'],
        Array $specifics_attributes = null,
        $snakeCase = true): void
    {
        $serializer = new Serializer([
            new DateTimeNormalizer(['Y-m-d']), 
            new ObjectNormalizer(
                new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())), 
                ($snakeCase) ? new CamelCaseToSnakeCaseNameConverter() : null, 
                null, 
                null, 
                null, 
                null, 
                [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                        return $object;
                }]
            )
        ]);

        $normalizer_filter = [
            'groups' => $groups,
        ];
        if ($specifics_attributes) {
            $normalizer_filter['attributes'] = $specifics_attributes;
        }

        $this->data = $serializer->normalize($object, null, $normalizer_filter);
    }

    public function toArray(): ?array
    {
        return $this->data;
    }

    public function toJson(): ?string
    {
        return json_encode($this->data);
    }
}
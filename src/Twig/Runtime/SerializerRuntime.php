<?php

namespace App\Twig\Runtime;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class SerializerRuntime implements RuntimeExtensionInterface
{
    private const JSON_FORMAT = 'json';
    private const JSONLD_FORMAT = 'jsonld';

    public function __construct(private readonly SerializerInterface $serializer)
    {
        // Inject dependencies if needed
    }

    public function serializeToJsonLd($value, $serializationGroups = null)
    {
        return $this->serialize(self::JSONLD_FORMAT, $value, $serializationGroups);
    }

    public function serializeToJson($value, $serializationGroups = null)
    {
        return $this->serialize(self::JSON_FORMAT, $value, $serializationGroups);
    }

    private function serialize($format, $value, $serializationGroups = null): string
    {
        return $this->serializer->serialize($value, $format, (new ObjectNormalizerContextBuilder())->withGroups($serializationGroups)->toArray());
    }
}
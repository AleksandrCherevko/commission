<?php

namespace App\Commission\Service\Serializer;

interface SerializerServiceInterface
{
    /**
     * @template InstanceType
     *
     * @param string                     $data
     * @param class-string<InstanceType> $type
     * @param string                     $format
     *
     * @return InstanceType
     */
    public function deserialize(string $data, string $type, string $format): object;
}

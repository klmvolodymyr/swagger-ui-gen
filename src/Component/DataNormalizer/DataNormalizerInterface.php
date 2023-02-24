<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\DataNormalizer;

interface DataNormalizerInterface
{
    /**
     * Must return normalized part of config or throw NormalizationException
     *
     * @param array $config
     *
     * @return array
     *
     * @throws NormalizationException
     */
    public function normalize(array $config): array;
}
<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Bundle\ModelHandler\PropertyNaming;

interface NamingStrategyInterface
{
    /**
     * @param string $property
     *
     * @return string
     */
    public function getName(string $property): string;
}
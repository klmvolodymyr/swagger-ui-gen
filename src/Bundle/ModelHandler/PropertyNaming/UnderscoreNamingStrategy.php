<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Bundle\ModelHandler\PropertyNaming;

class UnderscoreNamingStrategy
{
    /**
     * @param string $property
     *
     * @return string
     */
    public function getName(string $property): string
    {
        return strtolower(
            preg_replace('/[A-Z]/',  '_\\0', $property)
        );
    }
}
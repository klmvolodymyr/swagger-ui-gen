<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Bundle\ModelHandler\Schema\PhpDoc;

interface PhpDocParserInterface
{
    /**
     * @param string $className
     *
     * @return array
     */
    public function parse(string $className): array;
}
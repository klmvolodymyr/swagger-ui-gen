<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\DataProvider;

interface DataProviderInterface
{
    /**
     * @return array
     */
    public function getData(): array;
}
<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model;

class SecurityRequirement
{
    /**
     * @var array|array[]
     */
    private $fields = [];

    /**
     * @return array|array[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param array  $value
     */
    public function addField(string $name, array $value): void
    {
        $this->fields[$name] = $value;
    }
}
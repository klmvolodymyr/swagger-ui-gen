<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model\Common;

trait NameOptionalTrait
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
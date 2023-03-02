<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model\Common;

trait RequiredTrait
{
    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
}
<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model\Common;

trait RefTrait
{
    /**
     * @var string|null
     */
    protected $ref;

    /**
     * @return null|string
     */
    public function getRef():? string
    {
        return $this->ref;
    }

    /**
     * @param null|string $ref
     */
    public function setRef(?string $ref): void
    {
        $this->ref = $ref;
    }
}
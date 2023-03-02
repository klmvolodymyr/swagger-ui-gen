<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model;

class InfoContact
{
    use NameOptionalTrait, UrlTrait;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @return null|string
     */
    public function getEmail():? string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
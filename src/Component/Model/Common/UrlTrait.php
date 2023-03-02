<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model\Common;

trait UrlTrait
{
    /**
     * @var string|null
     */
    protected $url;

    /**
     * @return null|string
     */
    public function getUrl():? string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }
}
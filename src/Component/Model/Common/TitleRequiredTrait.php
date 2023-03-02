<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\Model\Common;

trait TitleRequiredTrait
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
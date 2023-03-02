<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Component\ModelHandler;

interface SwaggerBuilderInterface
{
    /**
     * @param Swagger $model
     * @param array   $data
     *
     * @return mixed
     */
    public function build(Swagger $model, array $data);
}
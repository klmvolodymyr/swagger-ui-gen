<?php

namespace VolodymyrKlymniuk\SwaggerUiGen\Bundle\ModelHandler\Operation;

interface OperationBuilderInterface
{
    /**
     * @param Operation $operation
     * @param array     $data
     *
     * @return mixed
     */
    public function build(Operation $operation, array $data);
}
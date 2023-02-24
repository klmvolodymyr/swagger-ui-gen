<?php

namespace VolodymyrKlymniuk\SwaggerUIGen\Bundle\ModelHandler\Swagger;

class DefinitionsBuilder implements SwaggerBuilderInterface, DataNormalizerInterface
{
    /**
     * @var SchemaFactory
     */
    private $schemaFactory;

    /**
     * DefinitionsBuilder constructor.
     *
     * @param SchemaFactory $schemaFactory
     */
    public function __construct(SchemaFactory $schemaFactory)
    {
        $this->schemaFactory = $schemaFactory;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    public function normalize(array $config): array
    {
        return isset($config['sf_object_definitions']) ? ['sf_object_definitions' => $config['sf_object_definitions']] : [];
    }

    /**
     * @param Swagger $swagger
     * @param array   $configs
     */
    public function build(Swagger $swagger, array $configs): void
    {
        foreach ($configs['sf_object_definitions'] ?? [] as $config) {
            $definition = $this->schemaFactory->createSchemaObject($config);
            $swagger->addDefinition($config['name'], $definition);
        }
    }
}
<?php

namespace VolodymyrKlymniuk\SwaggerUiGen\Bundle\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BuildersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $this->addToRegistry(
            $container,
            SchemaFactory::class,
            'swagger_uigen.model_builder.schema',
            'addBuilder'
        );
        $this->addToRegistry(
            $container,
            PathItemFactory::class,
            'swagger_uigen.model_builder.operation',
            'addOperationBuilder'
        );
        $this->addToRegistry(
            $container,
            SwaggerProvider::class,
            'swagger_uigen.swagger_data_normalizer',
            'addDataNormalizer'
        );
        $this->addToRegistry(
            $container,
            SwaggerProvider::class,
            'swagger_uigen.model_builder.swagger',
            'addSwaggerBuilder'
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $registryName
     * @param string           $tagName
     * @param string           $method
     */
    private function addToRegistry(ContainerBuilder $container, string $registryName, string $tagName, string $method): void
    {
        if (false === $container->hasDefinition($registryName)) {
            return;
        }

        $registryDef = $container->getDefinition($registryName);
        $taggedServices = $container->findTaggedServiceIds($tagName);
        if (is_array($taggedServices) && count($taggedServices) > 0) {
            foreach (array_keys($taggedServices) as $id) {
                $registryDef->addMethodCall($method, [new Reference($id)]);
            }
        }
    }
}
<?php

namespace VolodymyrKlymniuk\SwaggerUiGen\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class SwaggerController extends AbstractController
{
    /**
     * @param DataProviderInterface $configProvider
     * @param SwaggerProvider       $swaggerProvider
     *
     * @return JsonResponse
     */
    public function dataAction(DataProviderInterface $configProvider, SwaggerProvider $swaggerProvider): JsonResponse
    {
        $schema = $swaggerProvider->getSwaggerData($configProvider);

        return new JsonResponse($schema, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param DataProviderInterface $configProvider
     * @param SwaggerProvider       $swaggerProvider
     *
     * @return Response
     */
    public function dumpAction(DataProviderInterface $configProvider, SwaggerProvider $swaggerProvider): Response
    {
        $schema = $swaggerProvider->getSwaggerData($configProvider);
        class_exists('Symfony\Component\VarDumper\VarDumper')
            ? $responseData = VarDumper::dump($schema)
            : $responseData = '<pre>' . print_r($schema, true);

        return new Response($responseData);
    }

    /**
     * @param DataProviderInterface $configProvider
     * @param SwaggerProvider       $swaggerProvider
     *
     * @return JsonResponse
     */
    public function docAction(DataProviderInterface $configProvider, SwaggerProvider $swaggerProvider): Response
    {
        $filesystemLoader = new FilesystemLoader($this->getParameter('swagger_ui_gen.templates_path') . '/%name%');
        $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);

        $swaggerData = $swaggerProvider->getSwaggerData($configProvider);
        $swaggerDataJSON = json_encode($swaggerData, 15);

        $title = $swaggerData['info']['title'] ?? 'Swagger UI';

        return new Response($templating->render("index.html.php", ['title' => $title, 'swaggerDataJSON' => $swaggerDataJSON]));
    }
}
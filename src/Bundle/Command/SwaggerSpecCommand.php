<?php

namespace VolodymyrKlymniuk\SwaggerUiGen\Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SwaggerSpecCommand extends Command
{
    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * @var SwaggerProvider
     */
    private $swaggerProvider;

    /**
     * @param DataProviderInterface $dataProvider
     * @param SwaggerProvider $swaggerProvider
     */
    public function __construct(DataProviderInterface $dataProvider, SwaggerProvider $swaggerProvider)
    {
        parent::__construct('swagger-gen:generate-spec');

        $this->dataProvider = $dataProvider;
        $this->swaggerProvider = $swaggerProvider;
    }

    /**
     * Configure command
     */
    public function configure(): void
    {
        $defaultPath = __DIR__ . '/../../../../../../public/assets/swagger/data.json';
        $this
            ->setName('swagger-gen:generate-spec')
            ->setDescription('Command for generate json specification for swagger ui')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The destination of generated spec', $defaultPath)
            ->addOption('json_encode_options', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $swaggerSpec = $this->swaggerProvider->getSwaggerData($this->dataProvider);
        $jsonData = json_encode($swaggerSpec, $input->getOption('json_encode_options'));

        file_put_contents($input->getOption('path'), $jsonData);
    }
}
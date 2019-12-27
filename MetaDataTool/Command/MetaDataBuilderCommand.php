<?php

declare(strict_types=1);

namespace MetaDataTool\Command;

use MetaDataTool\JsonFileWriter;
use MetaDataTool\DocumentationCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MetaDataBuilderCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure(): void
    {
        $this
            ->setDescription('Scans the online ExactOnline documentation allowing to discover the API entities')
            ->setHelp(<<<'HELP'
                Scans the online ExactOnline documentation allowing to discover the API entities.....
HELP
            )->setDefinition([
                new InputOption('destination', 'd', InputOption::VALUE_REQUIRED, 'The destination directory', getcwd()),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $endpoints = (new DocumentationCrawler())->run();
        $writer = new JsonFileWriter($this->getFullDestinationPath($input->getOption('destination')));
        $writer->write($endpoints);

        return 0;
    }

    private function getFullDestinationPath(string $destination): string
    {
        if (strpos($destination, DIRECTORY_SEPARATOR) === 0) {
            return $destination;
        }

        return getcwd() . DIRECTORY_SEPARATOR . $destination;
    }
}

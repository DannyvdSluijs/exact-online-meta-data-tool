<?php

declare(strict_types=1);

namespace MetaDataTool\Command;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Crawlers\EndpointCrawler;
use MetaDataTool\Crawlers\MainPageCrawler;
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
                new InputOption(
                    'destination',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'The destination directory',
                    $this->defaultDestinationDirectory()
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Scanning main page');
        $mainPageCrawler = new MainPageCrawler('https://start.exactonline.nl/docs/HlpRestAPIResources.aspx');
        $pages = $mainPageCrawler->run();

        $output->writeln('Scanning entity pages');
        $config = new EndpointCrawlerConfig(true);
        $endpoints = (new EndpointCrawler($config, $pages))->run();
        $destination = $input->getOption('destination');
        if (! is_string($destination)) {
            throw new \RuntimeException('Invalid input for the destination option');
        }
        $writer = new JsonFileWriter($this->getFullDestinationPath($destination));
        $writer->write($endpoints);
        $output->writeln('Written meta data to ' . $writer->getFullFileName());

        return 0;
    }

    private function getFullDestinationPath(string $destination): string
    {
        if (strpos($destination, DIRECTORY_SEPARATOR) === 0 || strpos($destination, '.') === 0) {
            return $destination;
        }

        return $this->defaultDestinationDirectory() . DIRECTORY_SEPARATOR . $destination;
    }

    private function defaultDestinationDirectory(): string
    {
        if (strpos(__DIR__, 'vendor')) {
            return '.';
        }

        return getcwd();
    }
}

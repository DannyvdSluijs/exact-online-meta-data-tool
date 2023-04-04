<?php

declare(strict_types=1);

namespace MetaDataTool\Command;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Crawlers\EndpointCrawler;
use MetaDataTool\Crawlers\MainPageCrawler;
use MetaDataTool\JsonFileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MetaDataBuilderCommand extends Command
{
    private const MAINPAGE = 'https://start.exactonline.nl/docs/HlpRestAPIResources.aspx';
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
        $destination = $input->getOption('destination');
        $io = new SymfonyStyle($input, $output);
        $io->title('Exact Online Meta Data Tool');


        if (! is_string($destination)) {
            $io->error('Invalid input for the destination option');
            die(1);
        }

        $io->info(['Scanning main page', self::MAINPAGE]);
        $mainPageCrawler = new MainPageCrawler(self::MAINPAGE);
        $pages = $mainPageCrawler->run();

        $io->info('Scanning entity pages');
        $io->progressStart($pages->count());

        $config = new EndpointCrawlerConfig(true);
        $endpoints = (new EndpointCrawler($config, $pages))
            ->run(static function() use ($io): void {
                $io->progressAdvance(1);
            });
        $io->progressFinish();

        $io->info('Creating meta data file');
        $writer = new JsonFileWriter($this->getFullDestinationPath($destination));
        $writer->write($endpoints);
        $io->success('Written meta data to ' . $writer->getFullFileName());

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
        $currentWorkingDirectory = getcwd();

        if ($currentWorkingDirectory === false) {
            throw new \RuntimeException('Unable to determine current working directory.');
        }

        return $currentWorkingDirectory;
    }
}

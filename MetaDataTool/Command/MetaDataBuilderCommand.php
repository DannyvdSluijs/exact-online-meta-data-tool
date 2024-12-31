<?php

declare(strict_types=1);

namespace MetaDataTool\Command;

use MetaDataTool\Config\EndpointCrawlerConfig;
use MetaDataTool\Crawlers\EndpointCrawler;
use MetaDataTool\Crawlers\MainPageCrawler;
use MetaDataTool\Enum\KnownEntities;
use MetaDataTool\JsonFileWriter;
use MetaDataTool\ValueObjects\Endpoint;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'run')]
class MetaDataBuilderCommand extends Command
{
    private const string MAIN_PAGE = 'https://start.exactonline.nl/docs/HlpRestAPIResources.aspx';

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

        $io->info(['Scanning main page', self::MAIN_PAGE]);
        $mainPageCrawler = new MainPageCrawler(self::MAIN_PAGE);
        $pages = $mainPageCrawler->run();
        foreach (KnownEntities::cases() as $entity) {
            $pages->add('https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=' . $entity->value);
        }

        $io->info('Scanning entity pages');
        ProgressBar::setFormatDefinition('custom', ' %current%/%max% -- %message% (%url%)');
        $progressBar = $io->createProgressBar($pages->count());
        $progressBar->setFormat('custom');
        $progressBar->setMessage('Processing documentation');
        $progressBar->start();

        $config = new EndpointCrawlerConfig(true);
        $endpoints = (new EndpointCrawler($config, $pages))
            ->run(static function(Endpoint $endpoint) use ($progressBar): void {
                $progressBar->advance(1);
                $progressBar->setMessage($endpoint->getDocumentation(), 'url');
            });
        $progressBar->finish();
        $progressBar->setMessage('');
        $io->newLine(2);

        $io->info('Creating meta data file');
        $writer = new JsonFileWriter($this->getFullDestinationPath($destination));
        $writer->write($endpoints);
        $io->success('Written meta data to ' . $writer->getFullFileName());

        return 0;
    }

    private function getFullDestinationPath(string $destination): string
    {
        if (str_starts_with($destination, DIRECTORY_SEPARATOR) || str_starts_with($destination, '.')) {
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

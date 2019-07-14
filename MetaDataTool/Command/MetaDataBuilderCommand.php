<?php declare(strict_types=1);

namespace MetaDataTool\Command;

use MetaDataTool\JsonFileWriter;
use MetaDataTool\DocumentationCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $endpoints = (new DocumentationCrawler())->run();

        (new JsonFileWriter('./tmp'))->write($endpoints);

        return 0;
    }
}

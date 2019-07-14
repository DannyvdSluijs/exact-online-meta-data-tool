#!/usr/bin/env php
<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use MetaDataTool\Command\MetaDataBuilderCommand;
use Symfony\Component\Console\Application;

chdir(__DIR__);

$application = new Application();
$application->add(new MetaDataBuilderCommand());

$application->run();
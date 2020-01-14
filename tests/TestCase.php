<?php

declare(strict_types=1);

namespace MetaDataTool\Tests;

use Faker\Factory;
use Faker\Generator;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Generator */
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    protected function faker(): Generator
    {
        return self::$faker;
    }
}

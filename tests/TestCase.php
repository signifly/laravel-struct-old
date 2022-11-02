<?php

namespace Signifly\Struct\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Signifly\Struct\StructServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        $this->setApplicationKey();

        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            StructServiceProvider::class,
        ];
    }

    protected function setApplicationKey()
    {
        putenv('APP_KEY=mysecretkey');
    }

    protected function fixture(string $name): array
    {
        $json = file_get_contents(__DIR__.'/Fixtures/'.$name.'.json');

        return json_decode($json, true);
    }
}

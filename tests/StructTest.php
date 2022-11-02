<?php

namespace Signifly\Struct\Tests;

use Signifly\Struct\Struct;

class StructTest extends TestCase
{
    /** @test **/
    public function it_returns_the_struct_instance_from_the_container()
    {
        $struct = $this->app->make('struct');

        $this->assertInstanceOf(Struct::class, $struct);
    }

    /** @test **/
    public function it_returns_the_same_struct_instance_from_the_container()
    {
        $structA = $this->app->make('struct');
        $structB = $this->app->make('struct');

        $this->assertSame($structA, $structB);
    }

    /** @test **/
    public function it_memoizes_the_http_client()
    {
        $struct = $this->app->make('struct');

        $clientA = $struct->getHttpClient();
        $clientB = $struct->getHttpClient();

        $this->assertSame($clientA, $clientB);
    }

    /** @test **/
    public function it_updates_credentials_and_resets_client()
    {
        $struct = $this->app->make('struct');

        $clientA = $struct->getHttpClient();

        $struct = $struct->withCredentials('1234', '1234');

        $clientB = $struct->getHttpClient();

        $this->assertNotSame($clientA, $clientB);
    }
}

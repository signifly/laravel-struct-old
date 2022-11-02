<?php

namespace Signifly\Struct\Tests;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class MacrosTest extends TestCase
{
    /** @test **/
    public function it_registers_struct_webhooks_macro_on_route()
    {
        $this->assertTrue(Route::hasMacro('structWebhooks'));
    }

    /** @test **/
    public function it_register_struct_macros_on_request()
    {
        $this->assertTrue(Request::hasMacro('structShopDomain'));
        $this->assertTrue(Request::hasMacro('structHmacSignature'));
        $this->assertTrue(Request::hasMacro('structTopic'));
    }

    /** @test **/
    public function it_registers_endpoint_when_using_struct_webhooks_macro()
    {
        Route::structWebhooks();

        Route::getRoutes()->refreshNameLookups();

        $this->assertTrue(Route::has('struct.webhooks'));
    }
}

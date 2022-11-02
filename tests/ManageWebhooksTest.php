<?php

namespace Signifly\Struct\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Signifly\Struct\Factory;
use Signifly\Struct\REST\Resources\ApiResource;
use Signifly\Struct\Struct;

class ManageWebhooksTest extends TestCase
{
    private Struct $struct;

    public function setUp(): void
    {
        parent::setUp();

        $this->struct = Factory::fromConfig();
    }

    /** @test **/
    public function it_creates_a_webhook()
    {
        Http::fake([
            '*' => Http::response($this->fixture('webhooks.create')),
        ]);

        $resource = $this->struct->createWebhook($payload = [
            'topic' => 'orders/create',
            'address' => 'https://whatever.hostname.com/',
            'format' => 'json',
        ]);

        Http::assertSent(function (Request $request) {
            $this->assertEquals($this->struct->getBaseUrl().'/webhooks.json', $request->url());
            $this->assertEquals('POST', $request->method());

            return true;
        });
        $this->assertInstanceOf(ApiResource::class, $resource);
    }
}

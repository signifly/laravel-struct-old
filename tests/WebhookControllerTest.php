<?php

namespace Signifly\Struct\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Signifly\Struct\Exceptions\WebhookFailed;
use Signifly\Struct\Webhooks\SecretProvider;
use Signifly\Struct\Webhooks\Webhook;
use Signifly\Struct\Webhooks\WebhookValidator;

class WebhookControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::structWebhooks();
        Event::fake();
    }

    /** @test **/
    public function it_dispatches_an_event_relative_to_topic_name()
    {
        $response = $this->postJson($this->getUrl(), [], $this->getValidHeaders());

        $response->assertOk();
        Event::assertDispatched('struct-webhooks.products.created');
    }

    private function getUrl(): string
    {
        return route('struct.webhooks');
    }

    private function getValidHeaders(array $overwrites = []): array
    {
        return array_merge([
            Webhook::HEADER_EVENT_KEY => 'products.created',
        ], $overwrites);
    }
}

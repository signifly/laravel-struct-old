<?php

namespace Signifly\Struct\Webhooks;

use Illuminate\Http\Request;

class Webhook
{
    const HEADER_EVENT_KEY = 'X-Event-Key';

    protected string $eventKey;
    protected array $payload;
    
    public function __construct(string $eventKey, array $payload)
    {
        $this->eventKey = $eventKey;
        $this->payload = $payload;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function eventKey(): string
    {
        return $this->eventKey;
    }

    public function eventName(): string
    {
        return 'struct-webhooks.'. $this->eventKey();
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->structEventKey(),
            json_decode($request->getContent(), true)
        );
    }
}

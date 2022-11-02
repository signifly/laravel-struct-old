<?php

namespace Signifly\Struct\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Signifly\Struct\Webhooks\WebhookValidator;

class ValidateWebhook
{
    private WebhookValidator $webhookValidator;

    public function __construct(WebhookValidator $webhookValidator)
    {
        $this->webhookValidator = $webhookValidator;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->webhookValidator->validateFromRequest($request);

        return $next($request);
    }
}

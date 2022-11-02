<?php

namespace Signifly\Struct\Webhooks;

use Illuminate\Http\Request;
use Signifly\Struct\Exceptions\WebhookFailed;
use Signifly\Struct\Support\VerifiesWebhooks;

class WebhookValidator
{
    use VerifiesWebhooks;

    private SecretProvider $secretProvider;

    public function __construct(SecretProvider $secretProvider)
    {
        $this->secretProvider = $secretProvider;
    }

    public function validate(string $signature, string $domain, string $data): void
    {
        // Validate webhook secret presence
        $secret = $this->secretProvider->getSecret($domain);
        throw_if(empty($secret), WebhookFailed::missingSigningSecret());

        // Validate webhook signature
        throw_unless(
            $this->isWebhookSignatureValid($signature, $data, $secret),
            WebhookFailed::invalidSignature($signature)
        );
    }

    public function validateFromRequest(Request $request): void
    {
        // Validate event key presence
        $eventKey = $request->structEventKey();
        throw_unless($eventKey, WebhookFailed::missingEventKey());
        
        // $this->validate($signature, $request->shopifyShopDomain(), $request->getContent());
    }
}

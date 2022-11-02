<?php

namespace Signifly\Struct\Webhooks;

class ConfigSecretProvider implements SecretProvider
{
    public function getSecret(string $domain): string
    {
        return config('struct.webhooks.secret');
    }
}

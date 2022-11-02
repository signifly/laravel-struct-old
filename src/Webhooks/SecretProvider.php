<?php

namespace Signifly\Struct\Webhooks;

interface SecretProvider
{
    public function getSecret(string $domain): string;
}

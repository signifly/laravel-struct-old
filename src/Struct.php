<?php

namespace Signifly\Struct;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Signifly\Struct\REST\Actions\ManagesProducts;
use Signifly\Struct\REST\Cursor;
use Signifly\Struct\Support\MakesHttpRequests;
use Signifly\Struct\Support\TransformsResources;

class Struct
{
    use MakesHttpRequests;
    use ManagesProducts;
    use TransformsResources;

    protected string $apiKey;
    protected string $baseUri;

    protected ?PendingRequest $httpClient = null;

    public function __construct(string $apiKey, string $baseUri)
    {
        $this->withCredentials($apiKey, $baseUri);
    }

    public function cursor(Collection $results): Cursor
    {
        return new Cursor($this, $results);
    }

    public function getHttpClient(): PendingRequest
    {
        return $this->httpClient ??= Http::baseUrl($this->getBaseUrl())
            ->withToken($this->apiKey, '')
            ->acceptJson();
    }

    public function graphQl(): PendingRequest
    {
        return Http::baseUrl("https://{$this->domain}/admin/api/graphql.json")
            ->withHeaders(['X-Shopify-Access-Token' => $this->password]);
    }

    public function getBaseUrl(): string
    {
        return "https://{$this->baseUri}";
    }

    public function tap(callable $callback): self
    {
        $callback($this->getHttpClient());

        return $this;
    }

    public function withCredentials(string $apiKey, string $baseUri): self
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;

        $this->httpClient = null;

        return $this;
    }
}

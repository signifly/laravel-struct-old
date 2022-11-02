<?php

namespace Signifly\Struct\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Signifly\Struct\Exceptions\ErrorHandlerInterface;
use Signifly\Struct\REST\Resources\ApiResource;
use Signifly\Struct\Struct;

/**
 * @mixin Struct
 */
trait MakesHttpRequests
{
    protected Response $lastResponse;

    public function get(string $url, $query = null): Response
    {
        $response = $this->getHttpClient()->get($url, $query);

        $this->handleErrorResponse($response);

        return $response;
    }

    public function post(string $url, array $data = []): Response
    {
        $response = $this->getHttpClient()->post($url, $data);

        $this->handleErrorResponse($response);

        return $response;
    }

    public function put(string $url, array $data = []): Response
    {
        $response = $this->getHttpClient()->put($url, $data);

        $this->handleErrorResponse($response);

        return $response;
    }

    public function patch(string $url, array $data = []): Response
    {
        $response = $this->getHttpClient()->patch($url, $data);

        $this->handleErrorResponse($response);

        return $response;
    }

    public function delete(string $url, array $data = []): Response
    {
        $response = $this->getHttpClient()->delete($url, $data);

        $this->handleErrorResponse($response);

        return $response;
    }

    protected function resourceClassFor(string $resource): string
    {
        $resourceClass = Str::of($resource)
            ->studly()
            ->singular()
            ->prepend('Signifly\\Struct\\REST\\Resources\\')
            ->append('Resource');

        return class_exists($resourceClass) ? $resourceClass : ApiResource::class;
    }

    protected function createResource(string $resource, array $data, array $uriPrefix = []): array
    {
        $response = $this->post(implode('/', [...$uriPrefix, $resource]), $data);

        return $response->json();
    }

    protected function createResources(string $resource, array $data, array $uriPrefix = []): array
    {
        $response = $this->post(implode('/', [...$uriPrefix, $resource]), $data);

        return $response->json();
    }

    protected function getResources(string $resource, array $params, array $uriPrefix = []): Collection
    {
        $resourceClass = $this->resourceClassFor($resource);
        $response = $this->get(implode('/', [...$uriPrefix, "{$resource}.json"]), $params);

        return $this->transformCollection($response[Str::ucfirst($resource)], $resourceClass);
    }

    protected function getResource(string $resource, $resourceId, array $uriPrefix = []): ApiResource
    {
        // $key = Str::singular($resource);
        $resourceClass = $this->resourceClassFor($resource);

        $response = $this->get(implode('/', [...$uriPrefix, "{$resource}/{$resourceId}"]));

        return new $resourceClass($response->json(), $this);
    }

    protected function updateResource(string $resource, $resourceId, array $data, array $uriPrefix = []): ApiResource
    {
        $key = Str::singular($resource);
        $resourceClass = $this->resourceClassFor($resource);

        $response = $this->put(implode('/', [...$uriPrefix, "{$resource}/{$resourceId}.json"]), [$key => $data]);

        return new $resourceClass($response[$key], $this);
    }

    protected function updateResources(string $resource, $resourceId, array $data, array $uriPrefix = []): ApiResource
    {
        $key = Str::singular($resource);
        $resourceClass = $this->resourceClassFor($resource);

        $response = $this->put(implode('/', [...$uriPrefix, "{$resource}/{$resourceId}.json"]), [$key => $data]);

        return new $resourceClass($response[$key], $this);
    }

    protected function deleteResource(string $resource, $resourceId, array $uriPrefix = []): void
    {
        $this->delete(implode('/', [...$uriPrefix, "{$resource}/{$resourceId}.json"]));
    }

    public function getLastResponse(): Response
    {
        return $this->lastResponse;
    }

    private function handleErrorResponse(Response $response): void
    {
        $this->lastResponse = $response;

        app(ErrorHandlerInterface::class)->handle($response);
    }
}

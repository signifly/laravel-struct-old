<?php

namespace Signifly\Struct\REST\Actions;

use Illuminate\Support\Collection;
use Signifly\Struct\REST\Cursor;
use Signifly\Struct\REST\Resources\ImageResource;
use Signifly\Struct\REST\Resources\ProductResource;
use Signifly\Struct\REST\Resources\VariantResource;
use Signifly\Struct\Struct;

/** @mixin Struct */
trait ManagesProducts
{
    public function createProducts(array $data): array
    {
        return $this->createResource('products', $data);
    }

    public function getProducts(array $params = []): Collection
    {
        return $this->getResources('products', $params);
    }

    public function getProduct($productId): ProductResource
    {
        return $this->getResource('products', $productId);
    }

    public function updateProduct($productId, $data)
    {
        return $this->updateResource('products', $productId, $data);
    }

    public function updateProducts($data)
    {
        return $this->updateResources('products', $data);
    }

    public function deleteProduct($productId): void
    {
        $this->deleteResource('products', $productId);
    }

    public function deleteProducts($productIds): void
    {
        $this->deleteResource('products', $productIds);
    }

}

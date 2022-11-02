<?php

namespace Signifly\Struct\REST\Resources;

use Illuminate\Support\Collection;

class ProductResource extends ApiResource
{
    public function update(array $data): self
    {
        return $this->struct->updateProduct($this->id, $data);
    }

    public function delete(): void
    {
        $this->struct->deleteProduct($this->id);
    }
}

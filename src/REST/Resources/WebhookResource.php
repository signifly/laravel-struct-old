<?php

namespace Signifly\Struct\REST\Resources;

class WebhookResource extends ApiResource
{
    public function update(array $data): self
    {
        return $this->struct->updateWebhook($this->id, $data);
    }

    public function delete(): void
    {
        $this->struct->deleteWebhook($this->id);
    }
}

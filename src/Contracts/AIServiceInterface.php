<?php

declare(strict_types=1);

namespace RezaulHReza\AiService\Contracts;

interface AIServiceInterface
{
    public function withBaseUri(string $baseUri): self;

    public function withModel(string $model): self;

    public function withApiKey(string $apiToken): self;

    public function withPayload(array $payload): self;

    public function getResponse(): mixed;
}

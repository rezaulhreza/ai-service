<?php

declare(strict_types=1);

namespace RezaulHReza\AiService;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RezaulHReza\AiService\Contracts\AIServiceInterface;
use Throwable;

/**
 * Class HuggingFaceService
 *
 * Service for interacting with the Hugging Face API for various model types.
 */
class HuggingFaceService implements AIServiceInterface
{
    private string $model;

    private ?string $apiToken = null;

    private array $payload = [];

    private string $baseUri = 'https://api-inference.huggingface.co/models/';

    /**
     * Set the Hugging Face model for the request.
     *
     * @param  string  $model  Model name
     */
    public function withModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the Hugging Face model.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Set the API token for authorization.
     */
    public function withApiKey(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Get the API token.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiToken;
    }

    /**
     * Set the payload for the API request.
     */
    public function withPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get the payload.
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Set a custom base URI for the request if not the default Hugging Face endpoint.
     *
     * @param  string  $uri  Base URI
     */
    public function withBaseUri(string $uri): self
    {
        $this->baseUri = $uri;

        return $this;
    }

    /**
     * Get the base URI.
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Execute the API request and handle the response based on the model type.
     *
     * @return mixed Parsed response from the Hugging Face API
     *
     * @throws Throwable
     */
    public function getResponse(): mixed
    {
        return rescue(function () {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiToken}",
            ])->post($this->baseUri . $this->model, $this->payload);

            if ($response->failed()) {
                throw new \Exception('Failed to retrieve response from Hugging Face API.');
            }

            return $this->parseResponse($response);
        });
    }

    /**
     * Parses the API response based on the model type.
     */
    private function parseResponse(Response $response): mixed
    {
        return match (true) {
            str_contains($this->model, 'stable-diffusion') => $this->processImageResponse($response->body()),
            default => $response->body()
        };
    }

    /**
     * Processes an image response from the Hugging Face API.
     *
     * @param  string  $data  Raw image data
     * @return string Base64-encoded image data
     */
    private function processImageResponse(string $data): string
    {
        return 'data:image/png;base64,' . base64_encode($data);
    }
}

<?php

namespace RezaulHReza\AiService;

use RezaulHReza\AiService\Contracts\AIServiceInterface;

class AiService
{
    /**
     * Factory method to initialize an AIService based on the model type.
     *
     * @param  string  $model  The model name (e.g., 'gpt-4', 'stable-diffusion').
     * @param  string  $apiToken  The API token to authenticate the request.
     *
     * @throws \InvalidArgumentException If the model type is not supported.
     */
    public static function make(string $model, string $apiToken): AIServiceInterface
    {
        $supportedModels = [
            'huggingface' => HuggingFaceService::class,
        ];

        $selectedService = null;

        // Determine the service to use based on the model string
        foreach ($supportedModels as $key => $serviceClass) {
            if (str_contains($model, $key)) {
                $selectedService = new $serviceClass;
                break;
            }
        }

        if (! $selectedService) {
            throw new \InvalidArgumentException("Model type '$model' is not supported.");
        }

        return $selectedService->withModel($model)->withApiKey($apiToken);
    }
}

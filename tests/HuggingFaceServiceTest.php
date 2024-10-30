<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use RezaulHReza\AiService\HuggingFaceService;

class HuggingFaceServiceTest extends TestCase
{
    /** @test */
    public function it_sets_and_retrieves_model()
    {
        $service = new HuggingFaceService();
        $service->withModel('stable-diffusion-v1');

        $this->assertEquals('stable-diffusion-v1', $service->getModel());
    }

    /** @test */
    public function it_sets_and_retrieves_api_token()
    {
        $service = new HuggingFaceService();
        $service->withApiKey('test_api_key');

        $this->assertEquals('test_api_key', $service->getApiKey());
    }

    /** @test */
    public function it_sets_and_retrieves_payload()
    {
        $service = new HuggingFaceService();
        $payload = ['input' => 'Generate an image'];
        $service->withPayload($payload);

        $this->assertEquals($payload, $service->getPayload());
    }

    /** @test */
    public function it_sends_request_and_parses_image_response()
    {
        $base64Image = base64_encode('image_data');

        Http::fake([
            'https://api-inference.huggingface.co/models/stable-diffusion' => Http::response('image_data', 200),
        ]);

        $service = (new HuggingFaceService())
            ->withModel('stable-diffusion')
            ->withApiKey('test_api_key')
            ->withPayload(['input' => 'Generate an image']);

        $response = $service->getResponse();

        $this->assertEquals("data:image/png;base64,{$base64Image}", $response);
    }

    /** @test */
    public function it_returns_raw_response_for_unknown_model()
    {
        Http::fake([
            'https://api-inference.huggingface.co/models/unknown-model' => Http::response('some_response', 200),
        ]);

        $service = (new HuggingFaceService())
            ->withModel('unknown-model')
            ->withApiKey('test_api_key')
            ->withPayload(['input' => 'Some input']);

        $response = $service->getResponse();

        $this->assertSame('some_response', $response);
    }
}

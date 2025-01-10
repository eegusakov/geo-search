<?php

declare(strict_types=1);

namespace GeoSearch\Engines\WeatherApi;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\StreamFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @internal
 */
#[CoversClass(WeatherApiSearchEngine::class)]
final class WeatherApiSearchEngineTest extends TestCase
{
    private StreamFactoryInterface $streamFactory;

    protected function setUp(): void
    {
        $this->streamFactory = new StreamFactory();
    }

    public function testSuccess(): void
    {
        $data = json_encode([
            'location' => [
                'lat' => 55.75,
                'lon' => 37.62,
                'name' => 'Moscow',
                'region' => 'Moscow City',
                'country' => 'Russia',
                'tz_id' => 'Europe/Moscow',
                'localtime' => '2023-07-12 0:11',
            ],
        ]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new WeatherApiSearchEngine(
            '12345678',
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('Moscow');

        $this->assertNotNull($geo);
    }

    public function testNotFound(): void
    {
        $data = json_encode([
            'error' => [
                'code' => 1006,
                'message' => 'No matching location found.',
            ],
        ]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new WeatherApiSearchEngine(
            '12345678',
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('gjdfnvks');

        $this->assertSame([], $geo);
    }

    public function testFoundEmpty(): void
    {
        $data = json_encode([]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new WeatherApiSearchEngine(
            '12345678',
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('Moscow');

        $this->assertSame([], $geo);
    }
}

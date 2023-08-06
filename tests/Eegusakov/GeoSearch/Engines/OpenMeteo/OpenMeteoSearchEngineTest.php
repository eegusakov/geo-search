<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines\OpenMeteo;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @covers \Eegusakov\GeoSearch\Engines\OpenMeteo\OpenMeteoSearchEngine
 *
 * @internal
 */
final class OpenMeteoSearchEngineTest extends TestCase
{
    private StreamFactoryInterface $streamFactory;

    protected function setUp(): void
    {
        $this->streamFactory = new StreamFactory();
    }

    public function testSuccessNonCommercial(): void
    {
        $data = json_encode([
            'results' => [
                [
                    'id' => 524901,
                    'name' => 'Moscow',
                    'latitude' => 55.75222,
                    'longitude' => 37.61556,
                    'elevation' => 144,
                    'feature_code' => 'PPLC',
                    'country_code' => 'RU',
                    'admin1_id' => 524894,
                    'timezone' => 'Europe/Moscow',
                    'population' => 10381222,
                    'country_id' => 2017370,
                    'country' => 'Russia',
                    'admin1' => 'Moscow',
                ],
            ],
            'generationtime_ms' => 1.1299849,
        ]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new OpenMeteoSearchEngine(
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('Moscow');

        $this->assertNotNull($geo);
    }

    public function testSuccessCommercial(): void
    {
        $data = json_encode([
            'results' => [
                [
                    'id' => 524901,
                    'name' => 'Moscow',
                    'latitude' => 55.75222,
                    'longitude' => 37.61556,
                    'elevation' => 144,
                    'feature_code' => 'PPLC',
                    'country_code' => 'RU',
                    'admin1_id' => 524894,
                    'timezone' => 'Europe/Moscow',
                    'population' => 10381222,
                    'country_id' => 2017370,
                    'country' => 'Russia',
                    'admin1' => 'Moscow',
                ],
            ],
            'generationtime_ms' => 1.1299849,
        ]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new OpenMeteoSearchEngine(
            $client,
            new ResponseFromGeoDtoMapper(),
            '2345678'
        );

        $geo = $searchEngine->search('Moscow');

        $this->assertNotNull($geo);
    }

    public function testNotFound(): void
    {
        $data = json_encode([
            'error' => true,
            'reason' => 'Parameter count must be between 1 and 100.',
        ]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new OpenMeteoSearchEngine(
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('gjdfnvks');

        $this->assertNull($geo);
    }

    public function testFoundEmpty(): void
    {
        $data = json_encode(['generationtime_ms' => 0.18894672]);

        $response = new Response();
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($this->streamFactory->createStream($data));

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $searchEngine = new OpenMeteoSearchEngine(
            $client,
            new ResponseFromGeoDtoMapper()
        );

        $geo = $searchEngine->search('Moscow');

        $this->assertNull($geo);
    }
}

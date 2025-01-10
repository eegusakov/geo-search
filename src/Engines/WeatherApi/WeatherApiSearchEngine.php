<?php

declare(strict_types=1);

namespace GeoSearch\Engines\WeatherApi;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\MapperInterface;
use GeoSearch\Interfaces\SearchEngineInterface;
use Laminas\Diactoros\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

/**
 * The class contains all the basic logic for searching for geographical objects through the WeatherAPI service.
 *
 * @see https://www.weatherapi.com/api-explorer.aspx#tz
 */
final class WeatherApiSearchEngine implements SearchEngineInterface
{
    /**
     * Here is an example of creating a geo search using the WeatherApi service:.
     *
     *     $weatherApiGeoSearch = new WeatherApiSearchEngine(
     *         '<API_TOKEN>',
     *         new Client()
     *     )
     *
     * @param array{lang: string} $options
     */
    public function __construct(
        private readonly string $apiKey,
        private readonly ClientInterface $httpClient,
        private readonly MapperInterface $mapper = new ResponseFromGeoDtoMapper(),
        private array $options = [],
    ) {}

    /**
     * @return array<empty>|GeoDto[]
     *
     * @throws ClientExceptionInterface|\Exception
     */
    public function search(string $query): array
    {
        $url = 'https://api.weatherapi.com/v1/timezone.json?' . http_build_query([
            'key' => $this->apiKey,
            'lang' => $this->options['lang'] ?? 'RU',
            'q' => $query,
        ]);

        $request  = new Request($url, 'GET');
        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data)) {
            return [];
        }

        return array_map(fn ($item) => $this->mapper->map($item), [$data]);
    }
}

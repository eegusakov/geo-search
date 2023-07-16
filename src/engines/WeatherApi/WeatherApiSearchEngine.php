<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
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
     *     $weatherApiGeoSearch = new WeatherApiGeoSearch(
     *         '<API_TOKEN>',
     *         new Client(),
     *         new ResponseFromGeoDtoMapper()
     *     )
     *
     * @param array{lang: string} $options
     */
    public function __construct(
        private string $apiKey,
        private ClientInterface $httpClient,
        private ResponseFromGeoDtoMapper $mapper,
        private array $options = [],
    ) {
    }

    /**
     * @throws ClientExceptionInterface|\Exception
     */
    public function search(string $query): ?GeoDto
    {
        $url = 'https://api.weatherapi.com/v1/timezone.json?' . http_build_query([
            'key' => $this->apiKey,
            'lang' => $this->options['lang'] ?? 'RU',
            'q' => $query,
        ]);

        $request  = new Request($url, 'GET');
        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data)) {
            return null;
        }

        return $this->mapper->map($data);
    }
}

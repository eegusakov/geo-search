<?php

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use Exception;
use Laminas\Diactoros\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

/**
 * The class contains all the basic logic for searching for geographical objects through the WeatherAPI service
 *
 * @link https://www.weatherapi.com/api-explorer.aspx#tz
 */
class WeatherApiSearchEngine implements SearchEngineInterface
{
    /**
     * Here is an example of creating a geo search using the WeatherApi service:
     *
     *     $weatherApiGeoSearch = new WeatherApiGeoSearch(
     *         '<API_TOKEN>',
     *         new Client(),
     *         new ResponseFromGeoDtoMapper()
     *     )
     *
     * @param string $apiKey
     * @param ClientInterface $httpClient
     * @param ResponseFromGeoDtoMapper $mapper
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
     * @param string $query
     * @return GeoDto|null
     * @throws Exception|ClientExceptionInterface
     */
    public function search(string $query): ?GeoDto
    {
        $url = 'https://api.weatherapi.com/v1/timezone.json?' . http_build_query([
                'key' => $this->apiKey,
                'lang' => $this->options['lang'] ?? 'RU',
                'q' => $query
        ]);

        $request  = new Request($url, 'GET');
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data)) {
            return null;
        }

        return $this->mapper->map($data);
    }
}
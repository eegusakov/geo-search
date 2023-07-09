<?php

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\GeoSearchInterface;
use Exception;
use Psr\Http\Client\ClientInterface;

/**
 * The class contains all the basic logic for searching for geographical objects through the WeatherAPI service
 *
 * @link https://www.weatherapi.com/api-explorer.aspx#tz
 */
class WeatherApiGeoSearch implements GeoSearchInterface
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
     * @throws Exception
     */
    public function search(string $query): ?GeoDto
    {
        $resp = $this->httpClient->get('https://api.weatherapi.com/v1/timezone.json', [
            'query' => [
                'key' => $this->apiKey,
                'lang' => $this->options['lang'] ?? 'RU',
                'q' => $query
            ]
        ]);

        $data = json_decode($resp->getBody()->getContents(), true);

        if (empty($data)) {
            return null;
        }

        return $this->mapper->map($data);
    }
}
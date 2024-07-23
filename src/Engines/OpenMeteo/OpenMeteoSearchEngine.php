<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines\OpenMeteo;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use Laminas\Diactoros\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

/**
 * The class contains all the basic logic for searching for geographical objects through the OpenMeteo service.
 *
 * @see https://open-meteo.com/en/docs/geocoding-api#count=1&language=ru
 */
final class OpenMeteoSearchEngine implements SearchEngineInterface
{
    /**
     * Here is an example of creating a geo search using the OpenMeteo service:.
     *
     *     $openMeteoGeoSearch = new OpenMeteoSearchEngine(
     *         new Client(),
     *         new ResponseFromGeoDtoMapper(),
     *         '<API_TOKEN>', // Used only for commercial use of the service API
     *     )
     *
     * @param array{lang: string} $options
     * @param string|null $apiKey Used only for commercial use of the service API
     */
    public function __construct(
        private ClientInterface $httpClient,
        private ResponseFromGeoDtoMapper $mapper,
        private ?string $apiKey = null,
        private array $options = [],
    ) {}

    /**
     * @throws ClientExceptionInterface|\Exception
     */
    public function search(string $query): ?GeoDto
    {
        $baseUrl = $this->apiKey
            ? 'https://geocoding-customer-api.open-meteo.com/v1/search?'
            : 'https://geocoding-api.open-meteo.com/v1/search?';

        $url = $baseUrl . http_build_query([
            'name' => $query,
            'count' => 1,
            'language' => $this->options['lang'] ?? 'ru',
            'format' => 'json',
        ]);

        if (null !== $this->apiKey) {
            $url .= '&apikey=' . $this->apiKey;
        }

        $request  = new Request($url, 'GET');
        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data['results'])) {
            return null;
        }

        return $this->mapper->map($data);
    }
}

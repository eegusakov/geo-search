<?php

declare(strict_types=1);

namespace GeoSearch\Engines\OpenMeteo;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\MapperInterface;
use GeoSearch\Interfaces\SearchEngineInterface;
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
     *         '<API_TOKEN>', // Used only for commercial use of the service API
     *     )
     *
     * @param array{lang: string, count: int} $options
     * @param string|null $apiKey Used only for commercial use of the service API
     */
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly MapperInterface $mapper = new ResponseFromGeoDtoMapper(),
        private readonly ?string $apiKey = null,
        private array $options = [],
    ) {}

    /**
     * @return array<empty>|GeoDto[]
     *
     * @throws ClientExceptionInterface|\Exception
     */
    public function search(string $query): array
    {
        $baseUrl = $this->apiKey
            ? 'https://geocoding-customer-api.open-meteo.com/v1/search?'
            : 'https://geocoding-api.open-meteo.com/v1/search?';

        $url = $baseUrl . http_build_query([
            'name' => $query,
            'count' => $this->options['count'] ?? 1,
            'language' => $this->options['lang'] ?? 'ru',
            'format' => 'json',
        ]);

        if (null !== $this->apiKey) {
            $url .= '&apikey=' . $this->apiKey;
        }

        $request  = new Request($url, 'GET');
        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data['results'])) {
            return [];
        }

        return array_map(fn ($item) => $this->mapper->map($item), $data['results']);
    }
}

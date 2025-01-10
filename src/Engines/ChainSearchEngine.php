<?php

declare(strict_types=1);

namespace GeoSearch\Engines;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\SearchEngineInterface;

/**
 * The class allows you to use several engines at once to search for a geographical object.
 * In this case, the first result with a non-empty result will be returned as a response.
 */
final readonly class ChainSearchEngine implements SearchEngineInterface
{
    /** @var SearchEngineInterface[] $searchEngines */
    private array $searchEngines;

    /**
     * ChainGeoSearch accepts an array of search engines.
     *
     * Here is an example of creating a geo search using several services:
     *
     *     $geoSearchChain = new ChainGeoSearch(
     *         new WeatherApiGeoSearch(
     *             '<API_TOKEN>',
     *             new Client()
     *         ),
     *         new WeatherApiGeoSearch(
     *             '<API_TOKEN>',
     *             new Client()
     *         )
     *     );
     *
     * @param array{SearchEngineInterface[]} $searchEngines
     */
    public function __construct(
        SearchEngineInterface ...$searchEngines
    ) {
        $this->searchEngines = $searchEngines;
    }

    /**
     * @return array<empty>|GeoDto[]
     */
    public function search(string $query): array
    {
        foreach ($this->searchEngines as $searchEngine) {
            $geo = $searchEngine->search($query);
            if ([] !== $geo) {
                return $geo;
            }
        }

        return [];
    }
}

<?php

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;

/**
 * The class allows you to use several engines at once to search for a geographical object.
 * In this case, the first result with a non-empty result will be returned as a response.
 */
class ChainSearchEngine implements SearchEngineInterface
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
     *             new Client(),
     *             new ResponseFromGeoDtoMapper()
     *         ),
     *         new WeatherApiGeoSearch(
     *             '<API_TOKEN>',
     *             new Client(),
     *             new ResponseFromGeoDtoMapper()
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
     * @param string $query
     * @return GeoDto|null
     */
    public function search(string $query): ?GeoDto
    {
        foreach ($this->searchEngines as $searchEngine) {
            $geo = $searchEngine->search($query);
            if (null !== $geo) {
                return $geo;
            }
        }

        return null;
    }
}
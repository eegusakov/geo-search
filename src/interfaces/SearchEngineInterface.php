<?php

namespace Eegusakov\GeoSearch\Interfaces;

use Eegusakov\GeoSearch\Dto\GeoDto;

/**
 * Interface describing the structure of the search engine
 */
interface SearchEngineInterface
{
    /**
     * Method containing all the basic logic necessary to search for geographical objects
     *
     * @param string $query
     * @return GeoDto|null
     */
    public function search(string $query): ?GeoDto;
}
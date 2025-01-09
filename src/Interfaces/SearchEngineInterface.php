<?php

declare(strict_types=1);

namespace GeoSearch\Interfaces;

use GeoSearch\Dto\GeoDto;

/**
 * Interface describing the structure of the search engine.
 */
interface SearchEngineInterface
{
    /**
     * Method containing all the basic logic necessary to search for geographical objects.
     */
    public function search(string $query): ?GeoDto;
}

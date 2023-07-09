<?php

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;

/**
 * Interface describing the structure of mappers that provide correlation of input parameters with GeoDto properties
 */
interface MapperInterface
{
    /**
     * The main method describing the rules for correlating input parameters with GeoDto properties
     *
     * @param mixed $data
     * @return GeoDto
     */
    public function map(mixed $data): GeoDto;
}
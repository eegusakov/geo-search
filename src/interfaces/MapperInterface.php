<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Interfaces;

use Eegusakov\GeoSearch\Dto\GeoDto;

/**
 * Interface describing the structure of mappers that provide correlation of input parameters with GeoDto properties.
 */
interface MapperInterface
{
    /**
     * The main method describing the rules for correlating input parameters with GeoDto properties.
     */
    public function map(mixed $data): GeoDto;
}

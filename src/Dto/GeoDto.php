<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Dto;

/**
 * The basic structure of the search engine response.
 */
final class GeoDto
{
    /**
     * @param float $lat Latitude in decimal degree
     * @param float $lon Longitude in decimal degree
     * @param string $name Location name
     * @param string $region Region or state of the location, if availa
     * @param string $country Location country
     * @param string $timezone Time zone name
     * @param \DateTimeImmutable $localtime Local date and time
     */
    public function __construct(
        public float $lat,
        public float $lon,
        public string $name,
        public string $region,
        public string $country,
        public string $timezone,
        public \DateTimeImmutable $localtime,
    ) {
    }
}

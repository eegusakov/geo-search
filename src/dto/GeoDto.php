<?php

namespace Eegusakov\GeoSearch\Dto;

use DateTime;

/**
 * The basic structure of the search engine response
 */
class GeoDto
{
    /**
     * @param float $lat Latitude in decimal degree
     * @param float $lon Longitude in decimal degree
     * @param string $name Location name
     * @param string $region Region or state of the location, if availa
     * @param string $country Location country
     * @param string $timezone Time zone name
     * @param DateTime $localtime Local date and time
     */
    public function __construct(
        public float $lat,
        public float $lon,
        public string $name,
        public string $region,
        public string $country,
        public string $timezone,
        public DateTime $localtime,
    ) {
    }
}
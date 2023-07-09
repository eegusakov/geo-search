<?php

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use DateTime;
use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Engines\MapperInterface;
use Exception;

/**
 * The class correlates the result of the Weather Api response with the GeoDto properties
 */
class ResponseFromGeoDtoMapper implements MapperInterface
{
    /**
     * @param array $data
     * @return GeoDto
     * @throws Exception
     */
    public function map(mixed $data): GeoDto
    {
        return new GeoDto(
            lat: $data['location']['lat'],
            lon: $data['location']['lon'],
            name: $data['location']['name'],
            region: $data['location']['region'],
            country: $data['location']['country'],
            timezone: $data['location']['tz_id'],
            localtime: new DateTime($data['location']['localtime']),
        );
    }
}
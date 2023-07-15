<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\MapperInterface;

/**
 * The class correlates the result of the Weather Api response with the GeoDto properties.
 */
final class ResponseFromGeoDtoMapper implements MapperInterface
{
    /**
     * @param array $data
     *
     * @throws \Exception
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
            localtime: new \DateTimeImmutable($data['location']['localtime']),
        );
    }
}

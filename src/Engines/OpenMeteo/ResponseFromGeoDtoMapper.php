<?php

declare(strict_types=1);

namespace GeoSearch\Engines\OpenMeteo;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\MapperInterface;

/**
 * The class correlates the result of the OpenMeteo response with the GeoDto properties.
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
            lat: $data['results'][0]['latitude'],
            lon: $data['results'][0]['longitude'],
            name: $data['results'][0]['name'],
            region: $data['results'][0]['admin1'],
            country: $data['results'][0]['country'],
            timezone: $data['results'][0]['timezone'],
            localtime: new \DateTimeImmutable(
                'now',
                new \DateTimeZone($data['results'][0]['timezone'])
            ),
        );
    }
}

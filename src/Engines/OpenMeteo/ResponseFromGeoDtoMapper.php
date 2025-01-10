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
            lat: $data['latitude'],
            lon: $data['longitude'],
            name: $data['name'],
            region: $data['admin1'] ?? '',
            country: $data['country'],
            timezone: $data['timezone'],
            localtime: new \DateTimeImmutable(
                'now',
                new \DateTimeZone($data['timezone'])
            ),
        );
    }
}

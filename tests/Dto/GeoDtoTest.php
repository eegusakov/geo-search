<?php

declare(strict_types=1);

namespace GeoSearch\Dto;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GeoDto::class)]
final class GeoDtoTest extends TestCase
{
    #[TestWith([55.75, 37.62, 'Moscow', 'Moscow City', 'Russia', 'Europe/Moscow'])]
    #[TestWith([55.75, 37.62, 'Moscow', '', 'Russia', 'Europe/Moscow'])]
    public function testSuccess($lat, $lon, $name, $region, $country, $timezone): void
    {
        $geo = new GeoDto(
            lat: $lat,
            lon: $lon,
            name: $name,
            region: $region,
            country: $country,
            timezone: $timezone,
            localtime: new \DateTimeImmutable('2023-07-12 0:11')
        );

        $this->assertIsFloat($geo->lat);
        $this->assertIsFloat($geo->lon);
        $this->assertIsString($geo->name);
        $this->assertIsString($geo->region);
        $this->assertIsString($geo->country);
        $this->assertIsString($geo->timezone);
        $this->assertInstanceOf(\DateTimeImmutable::class, $geo->localtime);
    }
}

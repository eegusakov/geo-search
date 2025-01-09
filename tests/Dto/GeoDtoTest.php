<?php

declare(strict_types=1);

namespace GeoSearch\Dto;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \GeoSearch\Dto\GeoDto
 */
final class GeoDtoTest extends TestCase
{
    public function testSuccess(): void
    {
        $geo = new GeoDto(
            55.75,
            37.62,
            'Moscow',
            'Moscow City',
            'Russia',
            'Europe/Moscow',
            new \DateTimeImmutable('2023-07-12 0:11')
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

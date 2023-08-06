<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines\OpenMeteo;

use Eegusakov\GeoSearch\Dto\GeoDto;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Eegusakov\GeoSearch\Engines\OpenMeteo\ResponseFromGeoDtoMapper
 *
 * @internal
 */
final class ResponseFromGeoDtoMapperTest extends TestCase
{
    public function testSuccess(): void
    {
        $data = [
            'results' => [
                [
                    'id' => 524901,
                    'name' => 'Moscow',
                    'latitude' => 55.75222,
                    'longitude' => 37.61556,
                    'elevation' => 144,
                    'feature_code' => 'PPLC',
                    'country_code' => 'RU',
                    'admin1_id' => 524894,
                    'timezone' => 'Europe/Moscow',
                    'population' => 10381222,
                    'country_id' => 2017370,
                    'country' => 'Russia',
                    'admin1' => 'Moscow',
                ],
            ],
            'generationtime_ms' => 1.1299849,
        ];

        $mapper = new ResponseFromGeoDtoMapper();
        $geo = $mapper->map($data);

        $this->assertInstanceOf(GeoDto::class, $geo);

        $this->assertSame($data['results'][0]['latitude'], $geo->lat);
        $this->assertIsFloat($geo->lat);

        $this->assertSame($data['results'][0]['longitude'], $geo->lon);
        $this->assertIsFloat($geo->lon);

        $this->assertSame($data['results'][0]['name'], $geo->name);
        $this->assertIsString($geo->name);

        $this->assertSame($data['results'][0]['admin1'], $geo->region);
        $this->assertIsString($geo->region);

        $this->assertSame($data['results'][0]['country'], $geo->country);
        $this->assertIsString($geo->country);

        $this->assertSame($data['results'][0]['timezone'], $geo->timezone);
        $this->assertIsString($geo->timezone);

        $this->assertInstanceOf(\DateTimeImmutable::class, $geo->localtime);
    }
}

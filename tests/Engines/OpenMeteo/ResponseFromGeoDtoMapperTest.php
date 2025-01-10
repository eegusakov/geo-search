<?php

declare(strict_types=1);

namespace GeoSearch\Engines\OpenMeteo;

use GeoSearch\Dto\GeoDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ResponseFromGeoDtoMapper::class)]
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
        $geo = array_map(static fn ($item) => $mapper->map($item), $data['results']);

        $this->assertIsArray($geo);
        $this->assertContainsOnly(GeoDto::class, $geo);

        $this->assertSame($data['results'][0]['latitude'], $geo[0]->lat);
        $this->assertIsFloat($geo[0]->lat);

        $this->assertSame($data['results'][0]['longitude'], $geo[0]->lon);
        $this->assertIsFloat($geo[0]->lon);

        $this->assertSame($data['results'][0]['name'], $geo[0]->name);
        $this->assertIsString($geo[0]->name);

        $this->assertSame($data['results'][0]['admin1'], $geo[0]->region);
        $this->assertIsString($geo[0]->region);

        $this->assertSame($data['results'][0]['country'], $geo[0]->country);
        $this->assertIsString($geo[0]->country);

        $this->assertSame($data['results'][0]['timezone'], $geo[0]->timezone);
        $this->assertIsString($geo[0]->timezone);

        $this->assertInstanceOf(\DateTimeImmutable::class, $geo[0]->localtime);
    }
}

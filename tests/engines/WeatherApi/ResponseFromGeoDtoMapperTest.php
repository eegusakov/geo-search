<?php

namespace Eegusakov\GeoSearch\Engines\WeatherApi;

use Eegusakov\GeoSearch\Dto\GeoDto;
use PHPUnit\Framework\TestCase;

class ResponseFromGeoDtoMapperTest extends TestCase
{

    public function testSuccess(): void
    {
        $data = [
            'location' => [
                'lat' => 55.75,
                'lon' => 37.62,
                'name' => 'Moscow',
                'region' => 'Moscow City',
                'country' => 'Russia',
                'tz_id' => 'Europe/Moscow',
                'localtime' => '2023-07-12 0:11',
            ]
        ];

        $mapper = new ResponseFromGeoDtoMapper();
        $geo = $mapper->map($data);

        $this->assertInstanceOf(GeoDto::class, $geo);

        $this->assertSame($data['location']['lat'], $geo->lat);
        $this->assertIsFloat($geo->lat);

        $this->assertSame($data['location']['lon'], $geo->lon);
        $this->assertIsFloat($geo->lon);

        $this->assertSame($data['location']['name'], $geo->name);
        $this->assertIsString($geo->name);

        $this->assertSame($data['location']['region'], $geo->region);
        $this->assertIsString($geo->region);

        $this->assertSame($data['location']['country'], $geo->country);
        $this->assertIsString($geo->country);

        $this->assertSame($data['location']['tz_id'], $geo->timezone);
        $this->assertIsString($geo->timezone);

        $this->assertSame(strtotime($data['location']['localtime']), $geo->localtime->getTimestamp());
        $this->assertInstanceOf(\DateTime::class, $geo->localtime);
    }
}

# Geo Search

![GitHub](https://img.shields.io/github/license/eegusakov/geo-search)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/eegusakov/geo-search)
![GitHub all releases](https://img.shields.io/github/downloads/eegusakov/geo-search/total)
![GitHub issues](https://img.shields.io/github/issues/eegusakov/geo-search)

**Geo Search** - PHP библиотека, которая позволит вам определить географическое расположение объекта, на основании переданных вами данных.

Для поиска используются API сторонних сервисов, поэтому входные данные для поиска могут отличаться в зависимости от сервиса. Весь перечень доступных сервисов указан ниже.

## Совместимость с PSR
Для обеспечения http запросов подойдет любой клиент, совместимый с PSR-18. В примерах будет использоваться библиотека [Guzzle](https://github.com/guzzle/guzzle)

Для обеспечения работы с кэшем подойдет любой клиент, совместимый с PSR-16. В примерах будет использоваться библиотек [SymfonyCache](https://github.com/symfony/cache)

Для логирования ошибок подойдет любой клиент, совместимый с PSR-3 ([Monolog](https://github.com/Seldaek/monolog) и тд.). В примерах будет использоваться ,входящий в текущую библиотеку, ConsoleLogger.

## Установка Geo Search

Рекомендуемый способ установки Geo Search — через
[Composer](https://getcomposer.org/).

```bash
composer require eegusakov/geo-search
```

## Сервисы
Внимание для работы с каждым сервисов необходимо получить токен доступа к API. Для его получения перейдите по ссылке приложенной к конкретному сервису

### WeatherApi
Ссылка на сервис: https://www.weatherapi.com/my/

Ссылка на документацию: https://www.weatherapi.com/api-explorer.aspx#tz

Производит поиск по следующим данным:
1. почтовый индекс США,
2. почтовый индекс Великобритании,
3. почтовый индекс Канады,
4. IP-адрес,
5. широта/долгота (десятичный градус)
6. название города

Пример:

```php
use GuzzleHttp\Client;
use Eegusakov\GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use Eegusakov\GeoSearch\Engines\WeatherApi\ResponseFromGeoDtoMapper;

$weatherApiSearchEngine = new WeatherApiSearchEngine(
    '<API_TOKEN>',
    new Client(),
    new ResponseFromGeoDtoMapper()
);

$geoByCity = $weatherApiSearchEngine->search('Москва');

$geoByCoordinates = $weatherApiSearchEngine->search('53,-0.12');

$geoByZipCode = $weatherApiSearchEngine->search('90201');
```

## Дополнительные возможности

**1. Использование сразу несколько сервисов**

Данная библиотека позволяет использовать сразу несколько сервисов и получить результат первого сервиса вернувшего не пустой ответ.

```php
use Eegusakov\GeoSearch\ChainSearchEngine;
use Eegusakov\GeoSearch\Engines\WeatherApi\ResponseFromGeoDtoMapper;
use Eegusakov\GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use GuzzleHttp\Client;

$chainSearchEngine = new ChainSearchEngine(
    new WeatherApiSearchEngine(
        '<API_TOKEN_1>',
        new Client(),
        new ResponseFromGeoDtoMapper()
    ),
    new WeatherApiSearchEngine(
        '<API_TOKEN_2>',
        new Client(),
        new ResponseFromGeoDtoMapper()
    )
);

$geo = $chainSearchEngine->search('Москва');
```

**2. Возможность игнорирования ошибок**

Функционал актуален, когда есть необходимость игнорировать ошибки и переходить к следующему запросу.

ErrorHandler обрабатывает все ошибки и записывает их в лог, в библиотеке включен базовый logger, который просто выводит информацию в консоль (ConsoleLogger).

Для логирования подойдет любой клиент, совместимый с PSR-3.

```php
use GuzzleHttp\Client;
use Eegusakov\GeoSearch\MuteSearchEngine;
use Eegusakov\GeoSearch\Handlers\ErrorHandler;
use Eegusakov\GeoSearch\Loggers\ConsoleLogger;
use Eegusakov\GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use Eegusakov\GeoSearch\Engines\WeatherApi\ResponseFromGeoDtoMapper;

$muteSearchEngine = new MuteSearchEngine(
    new WeatherApiSearchEngine(
        '<API_TOKEN>',
        new Client(),
        new ResponseFromGeoDtoMapper()
    ),
    new ErrorHandler(
        new ConsoleLogger()
    )
);

$geo = $muteSearchEngine->search('Москва');
```

**3. Возможность кэшировать результат ответа**

Для работы с кэшем подойдет любой клиент, совместимый с PSR-16. В примере будет использоваться SymfonyCache.

```php

```

**4. Возможность комбинировать 1-й, 2-й и 3-й пункт**

```php
use GuzzleHttp\Client;
use Eegusakov\GeoSearch\MuteSearchEngine;
use Eegusakov\GeoSearch\ChainSearchEngine;
use Eegusakov\GeoSearch\Handlers\ErrorHandler;
use Eegusakov\GeoSearch\Loggers\ConsoleLogger;
use Eegusakov\GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use Eegusakov\GeoSearch\Engines\WeatherApi\ResponseFromGeoDtoMapper;

$chainSearchEngine = new ChainSearchEngine(
    new MuteSearchEngine(
        new WeatherApiSearchEngine(
            '<API_TOKEN_1>',
            new Client(),
            new ResponseFromGeoDtoMapper()
        ),
        new ErrorHandler(
            new ConsoleLogger()
        )
    ),
    new MuteSearchEngine(
        new WeatherApiSearchEngine(
            '<API_TOKEN_2>',
            new Client(),
            new ResponseFromGeoDtoMapper()
        ),
        new ErrorHandler(
            new ConsoleLogger()
        )
    )
);

$geo = $chainSearchEngine->search('Москва');
```

## Сотрудничество

Pull requests приветствуются. Что касается серьезных изменений, пожалуйста, сначала откройте проблему, чтобы обсудить, что вы хотели бы изменить.

Пожалуйста, не забудьте соответствующим образом обновить тесты.

## Лицензия

Geo Search предоставляется по лицензии MIT. Пожалуйста, посмотри [LICENSE](LICENSE), чтобы получить больше информации.
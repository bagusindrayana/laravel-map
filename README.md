# NEW UPDATE
I previously made this library for practice but some things got really messed up so I remade it from scratch

new library https://github.com/bagusindrayana/laravel-maps


# LARAVEL-MAP

> Laravel-Map is laravel package to help you to make and customize map from controller.laravel-map support mapbox and leaflet map
- Example : https://laravel-map.azurewebsites.net

## Installation

Use the package manager [composer](https://getcomposer.org) to install LARAVEL-MAP.

```bash
composer require bagusindrayana/laravel-map
```

Publish provider

```bash
php artisan vendor:publish --provider "Bagusindrayana\LaravelMap\LaravelMapServiceProvider"
```

If laravel < 5.5 add service provider and alias in config/app.php

```php
    'providers'=>[
        //....

        Bagusindrayana\LaravelMap\LaravelMapServiceProvider::class,

        //...
    ],
    'aliases'=>[
        //...

        'LaravelMap'=>Bagusindrayana\LaravelMap\LaravelMap::class

        //...
    ]


```

Add `MAPBOX_ACCESS_TOKEN` in .env or edit config/laravel-map.php



## Usage



In Controller

```php
#using mapbox
$laravelMap = new LaravelMap('mapbox',[
    'center'=>[-122.48695850372314, 37.82931081282506],
    'zoom'=>15,
    'style'=>'mapbox://styles/mapbox/dark-v10',
    'container'=>'map',
    'containerStyle'=>'width: 100%; height: 100%;position:absolute;top:0;bottom:0;'
]);

return view('your-map-view',compact('laravelMap'))

```

In View

```html

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Map</title>

        {!! $laravelMap->styles() !!}
      
    </head>
    <body >
        {!! $laravelMap->render() !!}
    </body>

    {!! $laravelMap->scripts() !!}
</html

```

## Current features

Mapbox - https://docs.mapbox.com/mapbox-gl-js/
- Marker
- MapboxGeocoder
- NavigationControl
- GeoLocateControl
- Popup
- Event

Leaflet - https://leafletjs.com/reference-1.7.1.html
- Marker
- Popup
- Polygon
- Circle
- GeoJson
- Event


DOCS : https://laravel-map-docs.netlify.app

EXAMPLE REPO : https://github.com/bagusindrayana/laravel-map-example
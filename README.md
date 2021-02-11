# LARAVEL-MAP

Laravel-Map is laravel package to help you to show map from backend.
laravel map support mapbox and leaflet map

## Installation

Use the package manager [composer](https://getcomposer.org) to install LARAVEL-MAP.

```bash
composer require bagusindrayana/laravel-map
```

Publish provider

```bash
php artisan vendor:publish --provider "BagusIndrayana\LaravelMap\LaravelMapServiceProvider"
```

If laravel < 5.5 add service provider and alias in config/app.php

```php
    'providers'=>[
        //....

        BagusIndrayana\LaravelMap\LaravelMapServiceProvider::class,

        //...
    ],
    'aliases'=>[
        //...

        'LaravelMap'=>BagusIndrayana\LaravelMap\LaravelMap::class

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

#### Mapbox https://docs.mapbox.com/mapbox-gl-js/
- Marker
- MapboxGeocoder
- NavigationControl
- Popup
- Event

### Leaflet https://leafletjs.com/reference-1.7.1.html
- Marker
- Popup
- Polygon
- Circle
- GeoJson
- Event

# Example

#### Using Leaflet

```php
$leaflet = new LaravelMap('leaflet',[
    'center'=>[55.665957,12.550343],
    'zoom'=>15,
    'container'=>'leaflet-map',
    'containerStyle'=>'width: 100%; height: 400px;'
]);
$leaflet->map->tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',[
    'attribution'=>'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
]);

return view('your-map-view',compact('leaflet'))

```

Add marker on load

```php
use BagusIndrayana\LaravelMap\Leaflet\Marker as LeafletMarker;

$leaflet->map->addEvent('load',function($m){
    $markers = [
        new LeafletMarker(["latLng"=>[55.665957,12.550343]]),
        new LeafletMarker(["latLng"=>[55.666067,12.551453]]),
        new LeafletMarker(["latLng"=>[55.667177,12.552563]])
    ];
    $m->addMarker($markers);
});
```

Add popup to marker

```php
$popup = new LeafletPopup(['offset'=>25,'content'=>'Hublaaaa']);
new LeafletMarker(["latLng"=>[55.665957,12.550343],'popup'=>$popup]);
```

Add circle and polygon
```php
$circle = new Circle([
    'latLng'=>[55.666067,12.551453],
    'color'=>'red',
    'fillColor'=>'#f03',
    'fillOpacity'=>0.5,
    'radius'=>500
]);
$m->giveTo($circle);

$polygon = new Polygon([
    'latLng'=>[
        [55.665957,12.550343],
        [55.666067,12.551453],
        [55.667177,12.552563]
    ],
    'color'=>'blue',
    'fillColor'=>'green',
    'fillOpacity'=>0.5,
]);
$m->giveTo($polygon);
```

#### Using Mapbox

Add srouce,layer,and click event

```php
$mapbox->map->addEvent('load',function($m){
    $m->addSource('titik',[
        'type'=>'geojson',
        'data'=>[
            'type'=>'FeatureCollection',
            'features'=>[
                [
                    'type'=>'Feature',
                    'properties'=>[
                        'description'=>'Hublaaa 1'
                    ],
                    'geometry'=>[
                        'type'=>'Point',
                        'coordinates'=>[12.550343, 55.665957]
                    ]
                ],
                [
                    'type'=>'Feature',
                    'properties'=>[
                        'description'=>'Hublaaa 2'
                    ],
                    'geometry'=>[
                        'type'=>'Point',
                        'coordinates'=>[12.551453, 55.666067]
                    ]
                ],
                [
                    'type'=>'Feature',
                    'properties'=>[
                        'description'=>'Hublaaa 3'
                    ],
                    'geometry'=>[
                        'type'=>'Point',
                        'coordinates'=>[12.552563, 55.667177]
                    ]
                ],

            ]
        ]
    ]);

    $m->loadImage("https://docs.mapbox.com/mapbox-gl-js/assets/custom_marker.png",function($m){
        $m->addLayer([
            'id'=>'titik',
            'type'=>'symbol',
            'source'=>'titik',
            'layout'=>[
                'icon-image'=> 'custom-marker',
                'icon-allow-overlap'=> true
            ]
        ]);
    });
    $popup = new Popup(['offset'=>25,'text'=>'Hublaaaa']);
    $m->addExtra('var popup = '.$popup->result().";");


    $m->addEvent(['click','titik'],function($mm){
        $mm->addExtra("
            $mm->varName.getCanvas().style.cursor = 'pointer';     
            var coordinates = e.features[0].geometry.coordinates.slice();
            var description = e.features[0].properties.description;
            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }
            popup.setLngLat(coordinates).setHTML(description).addTo($mm->varName);
        ");

    });
});
```

Add Navigation Control and GeoCoder

```php
$laravelMap = new LaravelMap('mapbox',[
    'center'=>[-122.48695850372314, 37.82931081282506],
    'zoom'=>15,
    'style'=>'mapbox://styles/mapbox/streets-v11',
    'container'=>'map',
    'containerStyle'=>'width: 600px; height: 400px;'
]);

$laravelMap->scripts = [
    'https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js',
    'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js',
    'https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js',
    'https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js'
];
$laravelMap->styles = [
    'https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css',
    'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css'
];

$c = new NavigationControl();
$g = new MapboxGeocoder([
    'zoom'=>4,
    'placeholder'=>'Cari Sesuatu...',
    'localGeocoder'=>'coordinatesGeocoder',
    'marker'=>[
        'color'=>'blue'
    ]
]);

$laravelMap->map->addControl([$c,$g]);
```


## License
[MIT](https://choosealicense.com/licenses/mit/)
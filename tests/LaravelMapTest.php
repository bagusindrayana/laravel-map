<?php declare(strict_types=1);

use Bagusindrayana\LaravelMap\LaravelMap;
use PHPUnit\Framework\TestCase;


final class LaravelMapTest extends TestCase
{   
 
    public function testMapbox(): void
    {   
        $mapboxMap = new LaravelMap('mapbox',[
            'center'=>[12.550343, 55.665957],
            'zoom'=>15,
            'style'=>'mapbox://styles/mapbox/streets-v11',
            'container'=>'mapbox-map',
            'containerStyle'=>'width: 100%; height: 400px;'
        ]);
        $result = "mapboxgl.accessToken = ''; var mapboxMap = new mapboxgl.Map({ accessToken:'', style:'mapbox://styles/mapbox/streets-v11', container:'mapbox-map', zoom:15, center:[12.550343,55.665957], });";
        $this->assertEquals($mapboxMap->map->extra,$result);
    }

    public function testLeaflet(): void
    {   
        $leaflet = new LaravelMap('leaflet',[
            'center'=>[55.665957,12.550343],
            'zoom'=>15,
            'container'=>'leaflet-map',
            'containerStyle'=>'width: 100%; height: 400px;'
        ]);
       
        $result = "var leafletMap = L.map('leaflet-map');";
        $this->assertEquals($leaflet->map->extra,$result);
    }

  
}
<?php
namespace BagusIndrayana\LaravelMap\MapBox;

class MapboxGeocoder
{   
    public $accessToken;
    public $localGeocoder;
    public $zoom;
    public $placeholder;
    public $mapboxgl;
    public $marker;
    //public $options;
    public $position;

    public function __construct($opts = null) {
        $this->setOption($opts);
        $this->accessToken = config('laravel-map.mapbox-access-token');
    }

    public function setOption($opts)
    {   
        foreach ($opts as $k => $v) {
            $this->$k = $v;
        }
    }

    public function getOptions()
    {
        return "{
            accessToken:'".$this->accessToken."',
            ".(($this->localGeocoder)? "localGeocoder:".$this->localGeocoder."," : "" )."
            zoom:'".($this->zoom ?? 5)."',
            ".(($this->placeholder)? "placeholder:'".$this->placeholder."'," : "" )."
            mapboxgl:".($this->mapboxgl ?? "mapboxgl").",
            ".(($this->marker)? "marker:".json_encode($this->marker)."," : "" )."
        }";
    }

    public function result()
    {   
        
        return 'new MapboxGeocoder('.$this->getOptions().','.$this->position.')';
    }
}
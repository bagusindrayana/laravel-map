<?php
namespace Bagusindrayana\LaravelMap\Leaflet;

class Circle
{   
    public $circleName = "circle";
    public $color;
    public $fillColor;
    public $fillOpacity;
    public $radius;

    //fun val
    public $latLng;
    public $map;
    public $text;

    public function __construct($opts = null) {
        $this->setOption($opts);
    }

    public function setOption($opts)
    {   
        if($opts){
            foreach ($opts as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    public function getOptions()
    {
        $opts = "{
            ".(($this->color)? "color:'".$this->color."'," : "" )."
            ".(($this->fillColor)? "fillColor:'".$this->fillColor."'," : "" )."
            ".(($this->fillOpacity)? "fillOpacity:".$this->fillOpacity."," : "" )."
            ".(($this->radius)? "radius:".$this->radius."," : "" )."
        }";

        return trim(preg_replace('/\s\s+/', ' ',$opts));
    }

    public function setLatLng($arr)
    {
        $this->latLng = $arr;
    }

    public function addTo($map)
    {
        $this->map = $map;
        return "var ".$this->circleName." = L.circle(".json_encode($this->latLng).",".$this->getOptions().")
        .addTo(".$this->map->varName.");";
    }



    public function result()
    {
        return "L.circle(".json_encode($this->latLng).",".$this->getOptions().")";
    }

  
}
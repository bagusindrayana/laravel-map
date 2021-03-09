<?php
namespace Bagusindrayana\LaravelMap\Leaflet;

class GeoJson
{   
    public $geoJsonName = "geoJson";
    public $json = [];
    public $popup;
    public $style;

    public $map;
    public function __construct($json,$style = null) {
        $this->json = $json;
        $this->$style = $style;
    }

    public function addTo($map)
    {
        $this->map = $map;
        return "var ".$this->geoJsonName." = new L.GeoJSON(".json_encode($this->json)."".(($this->style)?",style:".json_encode($this->style):"").")
        ".(($this->popup)? ".bindPopup(function (layer) {
            return ".$this->popup.";
        })" : "" )."
        .addTo(".$this->map->varName.");";
    }

    public function bindPopup($popup)
    {
        $this->popup = $popup();
    }


    public function result()
    {
        return "new L.GeoJSON(".json_encode($this->json).")
        ".(($this->popup)? ".bindPopup(".$this->popup.")" : "" )."";
    }

  
}
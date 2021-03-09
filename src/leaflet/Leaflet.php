<?php
namespace Bagusindrayana\LaravelMap\Leaflet;

use Bagusindrayana\LaravelMap\Js;

class Leaflet extends Js
{   
    public $css = "https://unpkg.com/leaflet@1.7.1/dist/leaflet.css";
    public $js = "https://unpkg.com/leaflet@1.7.1/dist/leaflet.js";
    public $varName = "leafletMap";
    public $accessToken;
    public $style;
    public $container;
    public $containerStyle;
    public $zoom;
    public $center;
    
    public $event;
    public $extra;

    public function __construct($opts = null) {
        $this->setOption($opts);
        $this->extra .= $this->baseJs();
        
    }

    public function baseJs()
    {
        $js = "
            var ".$this->varName." = L.map('".$this->container."');
        ";

        return $this->cleanScript($js);
    }

    public function renderScript()
    {
        //$scripts = $this->baseJs();
        
        $scripts = $this->extra ?? "";
        $scripts .= $this->event ?? "";
        $scripts .= $this->varName.".setView(".json_encode($this->center).",".$this->zoom.");";

        return $scripts;
    }

    public function getOptions()
    {
        # code...
    }

    public function setOption($opts)
    {
        foreach ($opts as $k => $v) {
            $this->$k = $v;
        }

        
    }

    public function tileLayer($url,$props)
    {
        $this->addExtra("L.tileLayer('$url',".json_encode($props).").addTo($this->varName);");
    }

    public function render()
    {
        return '<div id="'.$this->container.'" style="'.$this->containerStyle.'"></div>';
    }

    public function addExtra($extra)
    {
        $this->extra .= $extra;
    }

    public function inEvent($event)
    {
        $this->event .= $event;
    }

    public function addEvent($name,$fun)
    {   
        $this->event .= "$this->varName.on('$name', function () {";
        $fun($this);
        $this->event .= "});";
    }

    public function addMarker($m)
    {   $markers = '';
        if(is_array($m)){
            for ($i=0; $i < count($m); $i++) { 
                $marker = $m[$i];
                $marker->map = $this;
               
                $markers .= "var marker".$i." = ".$marker->result().";";
            }
        } else {
            $markers .= "var marker = ".$m->result().";";
        }

        $this->extra .= $markers;
         
    }

    public function giveTo($el)
    {   
        $map = $this;
        if(is_array($el)){
            foreach ($el as $k => $v) {
                $map->addExtra($v->addTo($map));
            }
        } else {
            $map->addExtra($el->addTo($map));
        }
        $this->extra = $map->extra;
    }

    public function locationPicker($opts = null)
    {   
        $var = (@$opts['varName'] ?? 'markerPicker');
        $this->event .= "let ".$var." = null;";
        $this->event .= "$this->varName.on('click', function (e) {";
        
        $this->event .= "if(".$var." == null){";
        $this->event .= $var." = new L.marker(e.latlng).addTo(".$this->varName.");";
        $this->event .= "} else {;"; 
        $this->event .= $var.".setLatLng(e.latlng);";   
        $this->event .= "}";   
        $this->event .= "document.getElementById('".(@$opts['inputId'] ?? 'coordinat')."').value = e.latlng.lat+','+e.latlng.lng;";
        $this->event .= "});";

        return '<input type="text" id="'.(@$opts['inputId'] ?? 'coordinat').'" class="'.@$opts['inputClass'].'" name="'.@$opts['inputName'].'">';
    }

    
}
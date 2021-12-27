<?php
namespace BagusIndrayana\LaravelMap\Leaflet;

class Leaflet
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

    private $eventInit;

    public function __construct($opts = null) {
        $this->setOption($opts);  
    }

    public function baseJs()
    {
        $js = "var ".$this->varName." = L.map('".$this->container."');\r\n";

        return $js;
    }

    public function renderScript()
    {
        $scripts = $this->baseJs();
        $scripts .= $this->event ?? "";
        $scripts .= $this->varName.".setView(".json_encode($this->center).",".$this->zoom.");\r\n";
        $scripts .= $this->extra ?? "";

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
        $this->addExtra("L.tileLayer('$url',".json_encode($props).").addTo($this->varName);\r\n");
    }

    public function render()
    {
        return '<div id="'.$this->container.'" style="'.$this->containerStyle.'"></div>';
    }

    public function addExtra($extra)
    {
        if(!$this->eventInit){
            $this->extra .= $extra;
        } else {
            $this->inEvent($extra);
        }
    }

    public function inEvent($event)
    {
        $this->event .= $event;
    }

    public function addEvent($name,$fun)
    {   
        $this->eventInit = true;
        $this->event .= "$this->varName.on('$name', function () {\r\n";
        $fun($this);
        $this->event .= "});\r\n";
        $this->eventInit = false;
    }

    public function addMarker($m)
    {   $markers = '';
        if(is_array($m)){
            for ($i=0; $i < count($m); $i++) { 
                $marker = $m[$i];
                $marker->map = $this;
               
                $markers .= "var marker".$i." = ".$marker->result().";\r\n";
            }
        } else {
            $markers .= "var marker = ".$m->result().";\r\n";
        }

        $this->addExtra($markers);
         
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
        if(!$this->eventInit){
            $this->extra = $map->extra;
        } else {
            $this->event = $map->event;
        }
        
    }

    public function locationPicker($opts = null)
    {   
        $var = (@$opts['varName'] ?? 'markerPicker');
        $this->event .= "let ".$var." = null;\r\n";
        $this->event .= "$this->varName.on('click', function (e) {\r\n";
        $this->event .= "if(".$var." == null){\r\n";
        $this->event .= $var." = new L.marker(e.latlng).addTo(".$this->varName.");\r\n";
        $this->event .= "} else {;"; 
        $this->event .= $var.".setLatLng(e.latlng);\r\n";   
        $this->event .= "}";   
        $this->event .= "document.getElementById('".(@$opts['inputId'] ?? 'coordinat')."').value = e.latlng.lat+','+e.latlng.lng;\r\n";
        $this->event .= "});\r\n";

        return '<input type="text" id="'.(@$opts['inputId'] ?? 'coordinat').'" class="'.@$opts['inputClass'].'" name="'.@$opts['inputName'].'">';
    }

    
}
<?php
namespace Bagusindrayana\LaravelMap\Leaflet;

class Marker
{   
    public $markerName = "marker";
    public $icon;
    public $keyboard;
    public $title;
    public $alt;
    public $zIndexOffset;
    public $opacity;
    public $riseOnHover;
    public $riseOffset;
    public $pane;
    public $shadowPane;
    public $bubblingMouseEvents;


    //fun val
    public $latLng;
    public $map;
    public $popup;

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
            ".(($this->icon)? "icon:".$this->icon."," : "" )."
            ".(($this->keyboard)? "anchor:".$this->keyboard."," : "" )."
            ".(($this->title)? "title:'".$this->title."'," : "" )."
            ".(($this->alt)? "alt:'".$this->alt."'," : "" )."
            ".(($this->zIndexOffset)? "zIndexOffset:".$this->zIndexOffset."," : "" )."
            ".(($this->opacity)? "opacity:".$this->opacity."," : "" )."
            ".(($this->riseOnHover)? "riseOnHover:".$this->riseOnHover."," : "" )."
            ".(($this->riseOffset)? "riseOffset:".$this->riseOffset."," : "" )."
            ".(($this->pane)? "pane:'".$this->pane."'," : "" )."
            ".(($this->shadowPane)? "shadowPane':".$this->shadowPane."'," : "" )."
            ".(($this->bubblingMouseEvents)? "bubblingMouseEvents:".$this->bubblingMouseEvents."," : "" )."
        }";
        return trim(preg_replace('/\s\s+/', ' ',$opts));
    }

    public function setLngLat($arr)
    {
        $this->latLng = $arr;
    }

    public function addTo($map)
    {
        $this->map = $map;
        return "var ".$this->markerName." = L.marker(".json_encode($this->latLng).",".$this->getOptions().")
        .addTo(".$this->map->varName.");";
    }
    public function setPopup($popup)
    {
        $this->popup = $popup;
    }


    public function result()
    {
        return "L.marker(".json_encode($this->latLng).",".$this->getOptions().")
        ".(($this->popup)? ".bindPopup(".$this->popup->result().").openPopup()" : "" )."
        .addTo(".$this->map->varName.")";
    }

  
}
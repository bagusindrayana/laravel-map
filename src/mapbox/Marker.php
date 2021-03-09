<?php
namespace BagusIndrayana\LaravelMap\MapBox;

class Marker
{   
    public $markerName = "marker";
    public $element;
    public $anchor;
    public $offset;
    public $color;
    public $scale;
    public $draggable;
    public $clickTolerance;
    public $rotation;
    public $pitchAlignment;
    public $rotationAlignment;


    //fun val
    public $lngLat;
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
            ".(($this->element)? "element:".$this->element."," : "" )."
            ".(($this->anchor)? "anchor:'".$this->anchor."'," : "" )."
            ".(($this->offset)? "offset:".$this->offset."," : "" )."
            ".(($this->color)? "color:'".$this->color."'," : "" )."
            ".(($this->scale)? "scale:".$this->scale."," : "" )."
        }";
        return trim(preg_replace('/\s\s+/', ' ',$opts));
    }

    public function setLngLat($arr)
    {
        $this->lngLat = $arr;
    }

    public function addTo($map)
    {
        $this->map = $map;
        return "var ".$this->markerName." = new mapboxgl.Marker(".$this->getOptions().")
        ".(($this->lngLat)?((is_array($this->lngLat))?".setLngLat(".json_encode($this->lngLat).")":(".setLngLat(".($this->lngLat)).")"):"")."
        .addTo(".$this->map->varName.");";
    }
    public function setPopup($popup)
    {
        $this->popup = $popup;
    }


    public function result()
    {
        return "new mapboxgl.Marker(".$this->getOptions().")
        ".(($this->lngLat)?((is_array($this->lngLat))?".setLngLat(".json_encode($this->lngLat).")":".setLngLat(".($this->lngLat)).")":"")."
        ".(($this->popup)? ".setPopup(".$this->popup->result().")" : "" )."
        .addTo(".$this->map->varName.")";
    }

  
}
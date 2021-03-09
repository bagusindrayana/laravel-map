<?php
namespace Bagusindrayana\LaravelMap\MapBox;

class Popup
{   
    public $popupName = "popup";
    public $closeButton;
    public $anchor;
    public $offset;
    public $closeOnMove;
    public $closeOnClick;
    public $focusAfterOpen;
    public $className;
    public $maxWidth;

    //fun val
    public $html;
    public $lngLat;
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
            ".(($this->closeButton)? "closeButton:".$this->closeButton."," : "" )."
            ".(($this->closeOnClick)? "closeOnClick:".$this->closeOnClick."," : "" )."
            ".(($this->closeOnMove)? "closeOnMove:".$this->closeOnMove."," : "" )."
            ".(($this->focusAfterOpen)? "focusAfterOpen:".$this->focusAfterOpen."," : "" )."
            ".(($this->anchor)? "anchor:".$this->anchor."," : "" )."
            ".(($this->offset)? "offset:".$this->offset."," : "" )."
            ".(($this->className)? "className:".$this->className."," : "" )."
            ".(($this->maxWidth)? "maxWidth:".$this->maxWidth."," : "" )."
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
        return "var ".$this->popupName." = new mapboxgl.Popup(".$this->getOptions().")
        ".(($this->html)? ".setHTML(".$this->html.")" : "" )."
        ".(($this->lngLat)?((is_array($this->lngLat))?".setLngLat(".json_encode($this->lngLat).")":".setLngLat(".($this->lngLat)).")":"")."
        ".(($this->text)? ".setText(".$this->text.")" : "" )."
        .addTo(".$this->map->varName.");";
    }

    public function setHTML($html)
    {
        $this->html = $html;
    }

    public function setText($text)
    {
        $this->text = $text;
    }


    public function result()
    {
        return "new mapboxgl.Popup(".$this->getOptions().")
        ".(($this->html)? ".setHTML(".$this->html.")" : "" )."
        ".(($this->lngLat)?((is_array($this->lngLat))?".setLngLat(".json_encode($this->lngLat).")":".setLngLat(".($this->lngLat)).")":"")."
        ".(($this->text)? ".setText('".$this->text."')" : "" );
    }

  
}
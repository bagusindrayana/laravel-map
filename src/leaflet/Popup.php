<?php
namespace BagusIndrayana\LaravelMap\Leaflet;

class Popup
{   
    public $popupName = "popup";
    public $maxWidth;
    public $minWidth;
    public $maxHeight;
    public $autoPan;
    public $autoPanPaddingTopLeft;
    public $autoPanPaddingBottomRight;
    public $autoPanPadding;
    public $keepInView;
    public $closeButton;
    public $autoClose;
    public $closeOnEscapeKey;
    public $closeOnClick;
    public $className;

    //fun val
    public $content;
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
            ".(($this->maxWidth)? "maxWidth:".$this->maxWidth."," : "" )."
            ".(($this->minWidth)? "minWidth:".$this->minWidth."," : "" )."
            ".(($this->maxHeight)? "maxHeight:".$this->maxHeight."," : "" )."
            ".(($this->autoPan)? "autoPan:".$this->autoPan."," : "" )."
            ".(($this->autoPanPaddingTopLeft)? "autoPanPaddingTopLeft:".$this->ancautoPanPaddingTopLefthor."," : "" )."
            ".(($this->autoPanPaddingBottomRight)? "autoPanPaddingBottomRight:".$this->autoPanPaddingBottomRight."," : "" )."
            ".(($this->autoPanPadding)? "autoPanPadding:".$this->autoPanPadding."," : "" )."
            ".(($this->keepInView)? "keepInView:".$this->keepInView."," : "" )."
       
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
        return "var ".$this->popupName." = L.popup(".$this->getOptions().")
        ".(($this->content)? ".setContent('".$this->content."')" : "" )."
        ".(($this->lngLat)? ".setLngLat(".json_encode($this->lngLat).")" : "" )."
        ".(($this->text)? ".setText(".$this->text.")" : "" )."
        .openOn(".$this->map->varName.");";
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setText($text)
    {
        $this->text = $text;
    }


    public function result()
    {
        return "L.popup(".$this->getOptions().")
        ".(($this->content)? ".setContent('".$this->content."')" : "" )."
        ".(($this->lngLat)? ".setLngLat(".json_encode($this->lngLat).")" : "" )."
        ".(($this->text)? ".setText('".$this->text."')" : "" );
    }

  
}
<?php
namespace Bagusindrayana\LaravelMap\Mapbox;

class NavigationControl
{   
    public $showCompass = true;
    public $showZoom = true;
    public $visualizePitch = true;

    public $position;

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
        return "{
            showCompass:".$this->showCompass.",
            showZoom:".$this->showZoom.",
            visualizePitch:".$this->visualizePitch.",
        }";
    }


    public function result()
    {
        return 'new mapboxgl.NavigationControl('.$this->getOptions().')';
    }

  
}
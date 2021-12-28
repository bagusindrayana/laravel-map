<?php
namespace Bagusindrayana\LaravelMap\Mapbox;

class GeolocateControl
{   
    public $positionOptions;
    public $fitBoundsOptions;
    public $trackUserLocation;
    public $showAccuracyCircle;
    public $showUserLocation;

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
        $opts = "{
            ".(($this->positionOptions)? "positionOptions:".json_encode($this->positionOptions)."," : "" )."
            ".(($this->fitBoundsOptions)? "fitBoundsOptions:".$this->fitBoundsOptions."," : "" )."
            ".(($this->trackUserLocation)? "trackUserLocation:".$this->trackUserLocation."," : "" )."
            ".(($this->showAccuracyCircle)? "showAccuracyCircle:".$this->showAccuracyCircle."," : "" )."
            ".(($this->showUserLocation)? "showUserLocation:".$this->showUserLocation."," : "" )."
        }";

        return trim(preg_replace('/\s\s+/', ' ',$opts));
    }


    public function result()
    {
        return 'new mapboxgl.GeolocateControl('.$this->getOptions().')';
    }

  
}
<?php
namespace Bagusindrayana\LaravelMap;

use Bagusindrayana\LaravelMap\Leaflet\Leaflet;
use Bagusindrayana\LaravelMap\MapBox\MapBox;

class LaravelMap
{   
    
    public $provider;
    public $map;
    public $styles = [];
    public $scripts = [];
    private $extra;


    public function __construct($provider = null,$opts = null) {
        if(is_array($provider)){
            $this->provider = array_keys($provider);
            $this->setMultipleMap($provider);
        } else {
            $this->provider = $provider;
            $this->setMap($provider,$opts);
        }
        
    }

    public function cleanScript($script)
    {
        return trim(preg_replace('/\s+/'," ",$script));
    }

    

    private function setMap($provider,$opts)
    {
        switch ($this->provider) {
            case 'mapbox':
                $mapbox = new MapBox($opts);
                $this->map = $mapbox;
                break;
            case 'leaflet':
                $leaflet = new Leaflet($opts);
                $this->map = $leaflet;
                break;
            default:
                return "canot find map...";
                break;
        }
    }

    private function setMultipleMap($maps)
    {   
        foreach ($maps as $key => $map) {
            switch ($key) {
                case 'mapbox':
                    $this->map[$map['container']] = new MapBox($map);
                    break;
                case 'leaflet':
                    $this->map[$map['container']] = new Leaflet($map);
                    break;
                default:
                    return "canot find map...";
                    break;
            }
        }
    }

    public function render($key = null)
    {
        if(is_array($this->provider)){
            return $this->map[$key]->render();
           
        } else {
            return $this->map->render();
        }
    }



    public function setStyles()
    {
        if(is_array($this->provider)){
            foreach ($this->map as $key => $map) {
                
                if(is_array($map->css)){
                    $this->styles = array_merge($this->styles,$map->css);
                } else {
                    $this->styles[] = $map->css;
                }
            }
        } else {
            $map = $this->map;
            if(is_array($map->css)){
                $this->styles = array_merge($this->styles,$map->css);
            } else {
                $this->styles[] = $map->css;
            }
        }
    }

    public function styles($styles = null)
    {   
        $this->setStyles();
        if($styles != null){
            $this->styles = $styles;
        }
        return view('laravel-map::styles',['styles'=>$this->styles]);
    }

    public function setScripts()
    {
        $this->extra = null;
        if(is_array($this->provider)){
            foreach ($this->map as $key => $map) {
                $this->extra .= $map->renderScript();
                if(is_array($map->js)){
                    $this->scripts = array_merge($this->scripts,$map->js);
                } else {
                    $this->scripts[] = $map->js;
                }
            }
        } else {
            $map = $this->map;
            $this->extra .= $map->renderScript();
            
            if(is_array($map->js)){
                $this->scripts = array_merge($this->scripts,$map->js);
            } else {
                $this->scripts[] = $map->js;
            }
        }
    }

    public function scripts($scripts = null)
    {   
        $this->setScripts();
        if($scripts != null){
            $this->scripts = $scripts;
        }
        return view('laravel-map::scripts',['scripts'=>$this->scripts,'extra'=>$this->extra]);
    }

    public function requestCurrentLocation($varName,$fun)
    {   
        
        $this->map->extra .= "if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function($varName){
                ";
        $fun($this->map);
        $this->map->extra .= "
            });
          } else {
            alert('Geolocation is not supported by this browser');
          }";
         
    }


    


}
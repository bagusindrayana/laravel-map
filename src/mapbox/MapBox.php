<?php
namespace Bagusindrayana\LaravelMap\MapBox;

use Bagusindrayana\LaravelMap\Js;

class MapBox extends Js
{   
    public $css = "https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css";
    public $js = "https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js";
    public $varName = "mapboxMap";
    public $accessToken;
    public $style;
    public $container;
    public $containerStyle;
    public $containerClass;
    public $zoom;
    public $center;
    public $minZoom;
    public $maxZoom;
    public $minPitch;
    public $maxPitch;
    public $hash;
    public $interactive;
    public $bearingSnap;
    public $pitchWithRotate;
    public $clickTolerance;
    public $attributionControl;
    public $customAttribution;
    public $logoPosition;
    public $failIfMajorPerformanceCaveat;
    public $preserveDrawingBuffer;
    public $antialias;
    public $refreshExpiredTiles;
    public $maxBounds;
    public $scrollZoom;
    public $boxZoom;
    public $dragRotate;
    public $dragPan;
    public $keyboard;
    public $doubleClickZoom;
    public $touchZoomRotate;
    public $touchPitch;
    public $trackResize;
    public $bearing;
    public $pitch;
    public $bounds;
    public $fitBoundsOptions;
    public $optimizeForTerrain;
    public $renderWorldCopies;
    public $maxTileCacheSize;
    public $localIdeographFontFamily;
    public $localFontFamily;
    public $transformRequest;
    public $collectResourceTiming;
    public $fadeDuration;
    public $crossSourceCollisions;
    public $locale;

    public $extra;

    public function __construct($opts = null) {
        $this->setOption($opts);
        $this->extra .= $this->baseJs();
       
    }

    public function baseJs()
    {
        $js = "mapboxgl.accessToken = '".(($this->accessToken)? $this->accessToken : (function_exists('config')?config('laravel-map.mapbox-access-token'):"") )."';\r\nvar ".$this->varName." = new mapboxgl.Map(".$this->getOptions().");\r\n";

        return $this->cleanScript($js);
    }

    public function renderScript()
    {
        // $scripts = $this->baseJs();
        $scripts = $this->extra ?? "";
        return $scripts;
    }

    public function getOptions()
    {   
        $opts = "{
            "."accessToken:'".(($this->accessToken)? $this->accessToken : (function_exists('config')?config('laravel-map.mapbox-access-token'):"") )."',
            ".(($this->style)? "style:'".$this->style."'," : "" )."
            ".(($this->container)? "container:'".$this->container."'," : "" )."
            ".(($this->zoom)? "zoom:".$this->zoom."," : "" )."
            ".(($this->center)?((is_array($this->center))?"center:".json_encode($this->center):"center:".$this->center).",":"")."
            ".(($this->minZoom)? "minZoom:".$this->minZoom."," : "" )."
            ".(($this->maxZoom)? "maxZoom:".$this->maxZoom."," : "" )."
            ".(($this->minPitch)? "minPitch:".$this->minPitch."," : "" )."
            ".(($this->maxPitch)? "maxPitch:".$this->maxPitch."," : "" )."
            ".(($this->hash)? "hash:".$this->hash."," : "" )."
            ".(($this->interactive)? "interactive:".$this->interactive."," : "" )."
            ".(($this->bearingSnap)? "bearingSnap:".$this->bearingSnap."," : "" )."
            ".(($this->pitchWithRotate)? "pitchWithRotate:".$this->pitchWithRotate."," : "" )."
            ".(($this->clickTolerance)? "clickTolerance:".$this->clickTolerance."," : "" )."
            ".(($this->attributionControl)? "attributionControl:".$this->attributionControl."," : "" )."
            ".(($this->customAttribution)? "customAttribution:".$this->customAttribution."," : "" )."
            ".(($this->logoPosition)? "logoPosition:'".$this->logoPosition."'," : "" )."
            ".(($this->failIfMajorPerformanceCaveat)? "failIfMajorPerformanceCaveat:".$this->failIfMajorPerformanceCaveat."," : "" )."
            ".(($this->preserveDrawingBuffer)? "preserveDrawingBuffer:".$this->preserveDrawingBuffer."," : "" )."
            ".(($this->antialias)? "antialias:".$this->antialias."," : "" )."
            ".(($this->refreshExpiredTiles)? "refreshExpiredTiles:".$this->refreshExpiredTiles."," : "" )."
            ".(($this->maxBounds)? "maxBounds:".$this->maxBounds."," : "" )."
            ".(($this->boxZoom)? "boxZoom:".$this->boxZoom."," : "" )."
            ".(($this->dragRotate)? "dragRotate:".$this->dragRotate."," : "" )."
            ".(($this->dragPan)? "dragPan:".$this->dragPan."," : "" )."
            ".(($this->keyboard)? "keyboard:".$this->keyboard."," : "" )."
            ".(($this->doubleClickZoom)? "doubleClickZoom:".$this->doubleClickZoom."," : "" )."
            ".(($this->touchZoomRotate)? "touchZoomRotate:".$this->touchZoomRotate."," : "" )."
            ".(($this->touchPitch)? "touchPitch:".$this->touchPitch."," : "" )."
            ".(($this->trackResize)? "trackResize:".$this->trackResize."," : "" )."
            ".(($this->bearing)? "bearing:".$this->bearing."," : "" )."
            ".(($this->pitch)? "pitch:".$this->pitch."," : "" )."
            ".(($this->bounds)? "bounds:".$this->bounds."," : "" )."
            ".(($this->fitBoundsOptions)? "fitBoundsOptions:".$this->fitBoundsOptions."," : "" )."
            ".(($this->optimizeForTerrain)? "optimizeForTerrain:".$this->optimizeForTerrain."," : "" )."
            ".(($this->renderWorldCopies)? "renderWorldCopies:".$this->renderWorldCopies."," : "" )."
            ".(($this->maxTileCacheSize)? "maxTileCacheSize:".$this->maxTileCacheSize."," : "" )."
            ".(($this->localIdeographFontFamily)? "maxTileCacheSize:'".$this->localIdeographFontFamily."'," : "" )."
            ".(($this->localFontFamily)? "localFontFamily:'".$this->localFontFamily."'," : "" )."
            ".(($this->transformRequest)? "transformRequest:".$this->transformRequest."," : "" )."
            ".(($this->collectResourceTiming)? "collectResourceTiming:".$this->collectResourceTiming."," : "" )."
            ".(($this->fadeDuration)? "fadeDuration:".$this->fadeDuration."," : "" )."
            ".(($this->crossSourceCollisions)? "crossSourceCollisions:".$this->crossSourceCollisions."," : "" )."
            ".(($this->locale)? "locale:".$this->locale."," : "" )."
        }";
        return $this->cleanScript($opts);
    }

    public function setOption($opts)
    {
        foreach ($opts as $k => $v) {
            $this->$k = $v;
        }
    }

    public function render()
    {
        return '<div id="'.$this->container.'" class="'.$this->containerClass.'" style="'.$this->containerStyle.'"></div>';
    }

    public function addControl($arr)
    {   
        $controls = '';
        for ($i=0; $i < count($arr); $i++) { 
            $a = $arr[$i];
            if(is_object($a)){
                $controls .= $this->varName.'.addControl('.$a->result().','.(($a->position)? '"'.$a->position.'"' : null).');\r\n';
               
            }
        }
        $this->extra .= $controls;
    }
    
    public function addExtra($extra)
    {   
        if(is_string($extra)){
            $this->extra .= $extra;
        } else if(is_object($extra)) {
            $this->extra .= $extra->result();
        } else {
            $this->extra .= $extra();
        }
        
    }

    public function addMarker($m)
    {   $markers = '';
        if(is_array($m)){
            for ($i=0; $i < count($m); $i++) { 
                $marker = $m[$i];
                if(is_object($marker)){
                    $marker->map = $this;
                    $markers .= "var marker".$i." = ".$marker->result().";\r\n";
                } else {
                    throw new \Exception("Parameter given is not Marker Object");
                }
            }
        } else {
            if(is_object($m)){
                $m->map = $this;
                $markers .= "var marker = ".$m->result().";";
            } else {
                throw new \Exception("Parameter given is not Marker Object");
            }
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

    public function addSource($layer,$prop)
    {   
        $this->extra .= "$this->varName.addSource('".$layer."', ".json_encode($prop).");\r\n";
    }

    public function addEvent($name,$fun)
    {   
        //$fun($this);
        $eventName = '';
        if(is_array($name)){
            $eventName = '';
            foreach ($name as $k => $v) {
                $eventName .= "'$v',";
            }
        } else {
            $eventName = "'$name',";
        }
        $this->extra .= "$this->varName.on($eventName function (e) {\r\n";
        $fun($this);
        $this->extra .= "});\r\n";
    }

    

    public function addLayer($prop)
    {
        $this->extra .= "$this->varName.addLayer(".json_encode($prop).");\r\n";
    }

    public function locationPicker($opts = null)
    {   
        $var = (@$opts['varName'] ?? 'markerPicker');
        $this->extra .= "
            let ".$var." = null;
            $this->varName.on('click', function(e) {
                if(".$var." == null){
                    ".$var." = ".(@$opts['marker']? $opts['marker']->result():'new mapboxgl.Marker()
                    .setLngLat(e.lngLat)
                    .addTo('.$this->varName.')').";
                } else {
                    ".$var.".setLngLat(e.lngLat);
                }
                lk = e.lngLat
                document.getElementById('".(@$opts['inputId'] ?? 'coordinat')."').value = e.lngLat.lat+','+e.lngLat.lng;
            });
        ";
        return '<input type="text" id="'.(@$opts['inputId'] ?? 'coordinat').'" class="'.@$opts['inputClass'].'" name="'.@$opts['inputName'].'">';
    }

    public function loadImage($url,$fun,$name = null)
    {
        $this->extra .= "$this->varName.loadImage('$url', function (error, image) { 
            if (error) throw error;
                $this->varName.addImage('".($name ?? 'custom-marker')."', image);\r\n";
        $fun($this);
        $this->extra .= "});\r\n";
    }

    public function addImage($id,$img,$opts = null)
    {   
        if(is_array($img)){
            $this->extra .= $this->varName.".addImage('".$id."',".$img["name"].(($opts)?",".json_encode($opts):"").");\r\n";
        } else {
            $this->extra .= $this->varName.".addImage('".$id."','".$img."'".(($opts)?"',".json_encode($opts):"").");\r\n";
        }
    }

    public function hasImage($id,$fun = null)
    {   
        if($fun == null){
            if(is_array($id)){
                return ((!isset($id[2]))?$id[1]:"").$this->varName.".hasImage('$id[0]')".((isset($id[2]))?$id[1].' '.$id[2]:"");
            } else {
                return $this->varName.".hasImage('$id')";
            }
        }
        if(is_array($id)){
            $this->extra .= "if(".((!isset($id[2]))?$id[1]:"").$this->varName.".hasImage('$id[0]')".((isset($id[2]))?$id[1].' '.$id[2]:"")."){";
                $fun($this);
            $this->extra .= "}";
            
        } else {
            $this->extra .= "if(".$this->varName.".hasImage('$id')){";
                $fun($this);
            $this->extra .= "}";
        }
    }

    public function updateImage($id,$img)
    {
        if(is_array($img)){
            $this->extra .= $this->varName.".updateImage('".$id."',".$img["name"].");\r\n";
        } else {
            $this->extra .= $this->varName.".updateImage('".$id."','".$img."'".");\r\n";
        }
    }

    public function flyTo($opts)
    {   
        $formatOpts = "{
            ".((isset($opts['center']))?((is_array($opts['center']))?"center:".json_encode($opts['center']):"center:".$opts['center']).",":"")."
            ".((isset($opts['zoom']))? "zoom:".$opts['zoom']."," : "" )."
            ".((isset($opts['speed']))? "speed:".$opts['speed']."," : "" )."
            ".((isset($opts['curve']))? "curve:".$opts['curve']."," : "" )."
            ".((isset($opts['minZoom']))? "minZoom:".$opts['minZoom']."," : "" )."
            ".((isset($opts['screenSpeed']))? "screenSpeed:".$opts['screenSpeed']."," : "" )."
            ".((isset($opts['maxDuration']))? "maxDuration:".$opts['maxDuration']."," : "" )."
            ".((isset($opts['bearing']))? "bearing:".$opts['bearing'] : "" )."
        }";
        $this->extra .= $this->varName.".flyTo(".trim(preg_replace('/\s\s+/', ' ',$formatOpts)).");";
    }
}
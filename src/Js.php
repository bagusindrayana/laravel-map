<?php
namespace BagusIndrayana\LaravelMap;


class Js
{   
    public $extra;
    public function __construct() {
        
    }

    public function if($check,$fun)
    {
        if(is_array($check)){
            $this->extra .= "if(".$check['name']."){";
        } else {
            $this->extra .= "if($check){";
        }
            $fun($this);
        $this->extra .= "}";
    }


}
<?php

namespace App;

use App\CustomModel;

class HppDevCostReport extends CustomModel
{
    public function item(){
    	return $this->belongsTo("App\Itempekerjaan","itempekerjaan");
    }
    
    public function cost_report(){
    	return $this->hasMany("App\CostReport","itempekerjaan");
    }
}

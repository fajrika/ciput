<?php

namespace App;

use App\CustomModel;

class SpkDetail extends CustomModel
{
    protected $fillable = ['spk_id','asset_id','asset_type','description'];

    public function spk()
    {
        return $this->belongsTo('App\Spk');
    }

    public function project()
    {
        if ($this->asset_type == 'App\Project') {
            
            return $this->belongsTo('App\Project', 'asset_id');

        }else{
            return NULL;
        }
    }
    public function kawasan()
    {
        if ($this->asset_type == 'App\ProjectKawasan') {
            
            return $this->belongsTo('App\ProjectKawasan', 'asset_id');

        }else{
            return NULL;
        }
    }
    public function unit()
    {
        if ($this->asset_type == 'App\Unit') {
            
            return $this->belongsTo('App\Unit', 'asset_id');

        }elseif($this->asset_type == 'App\Project'){
            
            return $this->belongsTo('App\Project', 'asset_id');

        }elseif($this->asset_type == 'App\ProjectKawasan'){

            return $this->belongsTo('App\ProjectKawasan', 'asset_id');
        }
    }

    public function details()
    {
        return $this->hasMany('App\SpkvoUnit')->where('head_type','App\Spk');
    }
    public function details_with_vo()
    {
        return $this->hasMany('App\SpkvoUnit');
    }

    public function rab_unit()
    {
        return $this->belongsTo('App\RabUnit','asset_id');
    }

    public function spkvo_unit()
    {
        return $this->belongsTo('App\SpkvoUnit', 'head_id');
    }

    public function asset()
    {
        return $this->morphTo();
    }

    public function rab_detail(){
        return $this->hasMany("App\RabPekerjaan","rab_unit_id");
    }

    public function getNilaiAttribute(){
        $nilai = 0;
        foreach ($this->rab_detail as $key => $value) {
            # code...
        }
        return $nilai;
    }
}

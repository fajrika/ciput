<?php

namespace App;

use App\CustomModel;

class SpkRetensi extends CustomModel
{
    protected $fillable = ['percent','hari','is_progress'];

    public function spk()
    {
        return $this->belongsTo('App\Spk');
    }
}

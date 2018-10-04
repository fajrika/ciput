<?php

namespace App;

use App\CustomModel;

class SpkPengembalian extends CustomModel
{
    protected $fillable = [
        'spk_id',
        'termin',
        'percent'
    ];

    public function spk()
    {
        return $this->belongsTo('App\Spk');
    }
}

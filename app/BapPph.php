<?php

namespace App;

use App\CustomModel;

class BapPph extends CustomModel
{
    protected $fillable = ['bap_id','coa_id','percent','description'];

    public function bap()
    {
        return $this->belongsTo('App\Bap');
    }

    public function coa()
    {
        return $this->belongsTo('App\Coa');
    }
}

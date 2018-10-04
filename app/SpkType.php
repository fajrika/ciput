<?php namespace App;

use App\CustomModel;

class SpkType extends CustomModel
{
	protected $fillable = ['description'];

    public function spks()
    {
        return $this->hasMany('App\Spk');
    }
}

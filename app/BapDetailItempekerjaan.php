<?php

namespace App;

use App\CustomModel;

class BapDetailItempekerjaan extends CustomModel
{
    protected $fillable = ['spkvo_unit_id','itempekerjaan_id','terbayar_percent','lapangan_percent'];

    public function bap_detail()
    {
        return $this->belongsTo('App\BapDetail');
    }

    public function itempekerjaan()
    {
        return $this->belongsTo('App\Itempekerjaan');
    }

    public function spkvo_unit()
    {
        return $this->belongsTo('App\SpkvoUnit');
    }

    public function getNilaiVoPercentageAttribute()
    {
        return $this->spkvo_unit->nilai_total * $this->terbayar_percent;
    }

    public function bap(){
        return $this->hasMany("App\Bap");
    }

    public function getVoPercentageSelisihAttribute(){
        $nilai = 0;
        $flag = "";
        $bap_sebelumnya = $this
            ->where("spkvo_unit_id",$this->spkvo_unit->id,"bap_detail_id")
            ->where("bap_detail_id",'<=',$this->bap_detail_id)
            ->get();

        foreach($bap_sebelumnya as $key => $value) {   
            if ( $flag == "" ){                    
                $nilai = ( $value->terbayar_percent * 100 ) - $nilai;    
                if ( $value->terbayar_percent * 100 == 100 ){
                    $flag = "1";
                }
            }else{
                return "0";
            }          
        }
        return $nilai;
    }
    
}

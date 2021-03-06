<?php

namespace Modules\Rab\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Approval;

class Rab extends Model
{

    use Approval;

    protected $fillable = ['workorder_id','no','name','flow','description','notes'];

    public function scopeProject()
    {
        return $this->whereHas('workorder', function($p)
        {
            $p->whereHas('budget_tahunan', function($q){
                $q->whereHas('budget', function($r){
                    $r->where('project_id', session('project'));
                });
            });
        });
    }
    public function getProjectAttribute()
    {
        return $this->workorder->project;
    }

    public function tenders()
    {
        return $this->hasMany('Modules\Tender\Entities\Tender');
    }
    
	public function units()
    {
        return $this->hasMany('Modules\Rab\Entities\RabUnit');
    }

    public function pekerjaans()
    {
        return $this->hasMany('Modules\Rab\Entities\RabPekerjaan', 'rab_unit_id');
    }
    
    public function workorder()
    {
        return $this->belongsTo('Modules\Workorder\Entities\Workorder');
    }

    public function getNilaiAttribute()
    {
        $nilai = 0;

        foreach ($this->pekerjaans as $key => $value) 
        {
            $nilai = $nilai + ( $value->nilai * $value->volume);
        }
        
        $nilai = $nilai * $this->units->count();
        return $nilai;
    }

    public function getPtAttribute()
    {
        return $this->workorder->pt;
    }

    public function getTemplatePekerjaanAttribute(){

        $templates = array();
        foreach ($this->pekerjaans as $key => $value) {
            # code...
            if ( $value->templatepekerjaan_detail_id != "" ){
                $templates[$key] = $value->templatepekerjaan_detail_id;
            }
            
        }

        return array_values(array_unique($templates));
    }

    public function getBlokListAttribute(){
        $bloklist = array();
        foreach ($this->units as $key => $value) {
            # code...
            if ( isset($value->asset->blok->id)){
                 $bloklist[$key] = $value->asset->blok->id;       
            }
               
        }

        return array_values(array_unique($bloklist));
    }

    public function getTotalUnitAttribute(){
        $nilai = 0;
        foreach ($this->units as $key => $value) {
            # code...
            $blok_id = $value->asset->blok_id;
            if ( $blok_id = 16 ){
                $nilai = $nilai + 1;
            }
        }
        return $nilai;
    }

    public function getParentIdAttribute(){
        $code = array();
        $id = "";
        if ( count($this->pekerjaans) > 0 ){
            foreach ($this->pekerjaans as $key => $value) {
                //echo $value->itempekerjaan->code;
                //echo "\n";
                $id_code = explode(".",$value->itempekerjaan->code);
                $code[$key] = $id_code[0].".".$id_code[1];
            }
            $unique = array_unique($code);
            $val = array_values($unique);
           
            $id = \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$val[0])->get()->first()->id;
        }
        
        return $id;
    }

    public function budget_tahunan(){
        return $this->belongsTo("\Modules\Budget\Entities\BudgetTahunan");
    }

    
}

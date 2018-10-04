<?php

namespace App;

use App\Model;

class ProjectPtUser extends CustomModel
{
    protected $fillable = ['user_id','pt_id','project_id','description'];

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function pt()
    {
        return $this->belongsTo('App\Pt', 'pt_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

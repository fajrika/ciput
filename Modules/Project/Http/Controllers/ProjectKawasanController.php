<?php

namespace Modules\Project\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Project\Entities\ProjectType;
use Modules\Blok\Entities\Blok;
use Modules\Project\Entities\Project;

class ProjectKawasanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $project = Project::find($request->id);
           
    }

    public function add(Request $request)
    {
        $project                        = Project::find($request->id);
        $project_types                   = ProjectType::get();

        return view('project_kawasan.add_form', compact('project', 'project_types'));
    }
    
    public function addPost(Request $request) 
    {
        $project_kawasan                         = new ProjectKawasan;
        $project_kawasan->project_id             = $request->edit_project_id;
        $project_kawasan->code                   = $request->code;
        $project_kawasan->name                   = $request->name;
        $project_kawasan->lahan_luas             = $request->lahan_luas;
        $project_kawasan->lahan_status           = $request->lahan_status;
        $project_kawasan->project_type_id        = $request->project_type_id;
        $project_kawasan->description            = $request->description;

        if ($request->is_kawasan) 
        {
            $project_kawasan->is_kawasan = TRUE;
        }else{
            $project_kawasan->is_kawasan = FALSE;
        }
        
        $status = $project_kawasan->save();

        if ($status) 
        {
            return redirect($to = 'project_kawasan/index?id='.$request->edit_project_id, $status = 302, $headers = [], $secure = null);
        }else{
            return 'Failed';
        }
    }

    public function edit(Request $request)
    {
        $project_kawasans   = ProjectKawasan::findOrFail($request->id);
        $projects           = Project::get();
        $project_types      = ProjectType::get();

        return view('project_kawasan.edit_form', compact('project_kawasans','projects','project_types'));
    }

    public function update(Request $request)
    {
        $project_kawasan                         = ProjectKawasan::find($request->id);
        $project_kawasan->code                   = $request->code;
        $project_kawasan->name                   = $request->name;
        $project_kawasan->lahan_luas             = $request->lahan_luas;
        $project_kawasan->lahan_status           = $request->lahan_status;
        $project_kawasan->hpptanahpermeter       = $request->hpptanahpermeter;
        $project_kawasan->project_type_id        = $request->project_type_id;
        $project_kawasan->description            = $request->description;
        
        if ($request->is_kawasan) 
        {
            $project_kawasan->is_kawasan = TRUE;
        }else{
            $project_kawasan->is_kawasan = FALSE;
        }

        $status                                  = $project_kawasan->save();

        if ($status) 
        {
            return redirect($to = 'project_kawasan/index?id='.$project_kawasan->project->id, $status = 302, $headers = [], $secure = null);
        }else{
            return 'Failed';
        }

    }

    public function delete(Request $request)
    {
        $project_kawasan                    = \App\ProjectKawasan::find($request->id);
        $status                             = $project_kawasan->delete();

        if ($status) 
        {
            return 'Deleted';
        }else{
            return 'Failed';
        }
    }

    
    public function detail(Request $request)
    {
        $project_kawasan                         = ProjectKawasan::find($request->id);
        $project                                 = Project::get();
        $project_blocks                          = Blok::get();

        return view('project_blocks.index', compact('project_blocks','project','project_kawasan'));
    }

    public function report(Request $request){
        $project = Project::findOrFail($request->id);
        $year    = $request->year;
        return view("project_kawasan.report",compact("project","year","request"));
    }

    public function show(Request $request, $id)
    {
        return $project_kawasan = ProjectKawasan::find($id)
            ->load('project.progresses','budgets','units.templatepekerjaan.details','progresses')
            ->setAppends([
                'nilai_dev_cost_budget',
                'nilai_dev_cost_kontrak',
                'nilai_con_cost_budget',
                'nilai_con_cost_kontrak',
                'dev_cost_budget_bruto',
                'dev_cost_budget_netto',
                'dev_cost_kontrak_netto',
                'hpp_con_cost_budget',
                'hpp_con_cost_kontrak'
            ])
            ->toArray();
    }

}
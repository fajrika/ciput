<?php

namespace Modules\PurchaseOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
    
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PO_POD =   DB::table("purchaseorders")
                    ->join("purchaseorder_details","purchaseorder_details.purchaseorder_id","purchaseorders.id")
                    ->join("items","items.id","purchaseorder_details.item_id")
                    ->select("purchaseorders.id","purchaseorders.no","purchaseorders.date","items.name","purchaseorders.description")
                    ->get();
        return view('purchaseorder::index',compact("user","project","PO_POD"));
    }
    public function detail(Request $request){
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        $PO_POD =   DB::table("purchaseorders")
                    ->where("purchaseorders.id",$request->id)
                    ->join("purchaseorder_details","purchaseorder_details.purchaseorder_id","purchaseorders.id")
                    ->join("items","items.id","purchaseorder_details.item_id")
                    ->join("brands","brands.id","purchaseorder_details.brand_id")
                    ->join("item_satuans","item_satuans.id","purchaseorder_details.item_satuan_id")
                    ->select("purchaseorders.id","purchaseorders.no","purchaseorders.date","items.name","purchaseorders.description","brands.name as bName","item_satuans.name as isName","purchaseorder_details.quantity")
                    ->first();
           
        return view('purchaseorder::detail',compact("user","project","PO_POD"));
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('purchaseorder::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('purchaseorder::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('purchaseorder::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

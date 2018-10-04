<?php

namespace Modules\Kontraktor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Rekanan\Entities\RekananGroup;
use Modules\User\Entities\User;
use Modules\Kontraktor\Entities\Kontraktor;
use Modules\Tender\Entities\Tender;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Tender\Entities\TenderRekanan;

class KontraktorController extends Controller
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
        $user = User::find(\Auth::user()->id);
        $rekanan = $user->rekanan;
        return view('kontraktor::index',compact("user","rekanan"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $tender = Tender::find($request->id);
        $user = User::find(\Auth::user()->id);
        $itempekerjaan = Itempekerjaan::find(292);
        $rekanans = $tender->rekanans;
        foreach ($rekanans as $key => $value) {
            if ( $value->rekanan->group->id == $user->rekanan->id ){
                $status = $value->is_pemenang;               
                $rekanan = TenderRekanan::find($value->id);
            }
        }

        return view('kontraktor::tender_detail',compact("tender","user","itempekerjaan","status","rekanan"));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function tender(Request $request)
    {
        $user = User::find(\Auth::user()->id);
        $tender = $user->rekanan->tender;
        return view("kontraktor::tender",compact("tender","user"));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function tenderadd(Request $request)
    {
        $tender_rekanan = TenderRekanan::find($request->id);
        $user = User::find(\Auth::user()->id);
        $tender = $tender_rekanan->tender;
        if ( count($tender_rekanan->penawarans) <= "0" ){
            $penawaran = "1";
        }else{
            $penawaran = count($tender_rekanan->penawarans);
        }
        $itempekerjaan = Itempekerjaan::find($tender->rab->parent_id);
        return view('kontraktor::tender_add',compact("user","tender","tender_rekanan","penawaran","itempekerjaan"));
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

<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Modules\Project\Entities\Project;
use Modules\Budget\Entities\Budget;
use Modules\Approval\Entities\ApprovalHistory;
use Modules\Workorder\Entities\Workorder;
use Modules\Pekerjaan\Entities\Itempekerjaan;
use Modules\Wokrorder\Entities\WorkorderBudgetDetail;
use Modules\Tender\Entities\Tender;
use Modules\Approval\Entities\Approval;
use Modules\Spk\Entities\Spk;
use Modules\Vo\Entities\Vo;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Blok\Entities\Blok;
use Modules\Project\Pekerjaan\Templatepekerjaan;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\BudgetTahunanTemplate;
use Modules\Budget\Entities\BudgetTahunanDetailtemplate;
use Modules\Rab\Entities\Rab;
use Modules\Department\Entities\Department;
use Modules\Rab\Tender\TenderKorespondensi;

class UserMasterController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }	


	public function project(Request $request){
		$project = Project::get();
		$user = \Auth::user();
		/* Get Budget Document */
		$approval = ApprovalHistory::where("user_id",$user->id)->where("approval_action_id",1)->get();
		$department = Department::get();
		return view("project.user.index",compact("approval","project","user","department"));
	}

	public function approval_summary(Request $request){
		$user = $request->user_id;
		$project = Project::find($request->project_id);

		/* Budget */
		$request_budget = 0;
		$approval_budget = 0;
		$rejected_budget = 0;
		foreach ($project->budgets as $key1 => $value1 ) {
			# code...
			$request_budget = $request_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
			$approval_budget = $approval_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
			$rejected_budget = $rejected_budget + $value1->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
		}
		$total_request_budget = $request_budget + $approval_budget + $rejected_budget;

		/* Workorder */
		$request_workorder = 0;
		$approval_workorder = 0;
		$rejected_workoder = 0;
		foreach ($project->workorders as $key => $value) {
			# code...
			$request_workorder = $request_workorder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
			$approval_workorder = $approval_workorder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
			$rejected_workoder = $rejected_workoder + $value->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
		}

		/* Tender Rekanan */
		$request_tender = 0;
		$approval_tender = 0;
		$rejected_tender = 0;
		$request_tender_rekanan = 0;
		$approval_tender_rekanan = 0;
		$rejected_tender_rekanan = 0;
		$tenders = $project->tenders->get();
		foreach ( $tenders as $key2 => $value2 ) {
			# code...
			$request_tender = $request_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
			$approval_tender = $approval_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
			$rejected_tender = $rejected_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
			foreach ($value2->rekanans as $key3 => $value3 ) {
				if (isset($value3->approval)){
					$request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
					$approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
					$rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
				}
			}
			$request_tender = $request_tender + $request_tender_rekanan;
			$approval_tender = $approval_tender + $approval_tender_rekanan;
			$rejected_tender = $rejected_tender + $rejected_tender_rekanan;

		}
		/*SPK*/
		$request_spk = 0;
		$approval_spk = 0;
		$rejected_spk = 0;

		foreach ($project->spks as $key4 => $value4) {
			# code...
			if (isset($value4->approval)){
				$request_spk = $request_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
				$approval_spk = $approval_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
				$rejected_spk = $rejected_spk + $value4->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
			}
		}

		/* Budget Tahunan*/
		$request_budget_tahunan = 0;
		$approval_budget_tahunan = 0;
		$rejected_budget_tahunan = 0;
		foreach ($project->budget_tahunans as $key6 => $value6 ) {
			# code...
			$request_budget_tahunan = $request_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
			$approval_budget_tahunan = $approval_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
			$rejected_budget_tahunan = $rejected_budget_tahunan + $value6->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
		}

		/* Budget Tahunan*/
		$request_rab = 0;
		$approval_rab = 0;
		$rejected_rab = 0;
		foreach ($project->rabs as $key7 => $value7 ) {
			# code...
			$request_rab = $request_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
			$approval_rab = $approval_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
			$rejected_rab = $rejected_rab + $value7->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
		}

		$total_request_budget_tahunan = $request_budget_tahunan + $approval_budget_tahunan + $rejected_budget_tahunan;
		$total_request_workorder = $request_workorder + $approval_workorder + $rejected_workoder;
		$total_request_rab = $request_rab + $approval_rab + $rejected_rab;
		$total_request_tender = $request_tender + $approval_tender + $rejected_tender;
		$total_request_spk = $request_spk + $approval_spk + $rejected_spk;

		return response()->json( [
			"request_budget" => $request_budget, "approval_budget" => $approval_budget, "rejected_budget" => $rejected_budget, "total_request_budget" => $total_request_budget, 
			"request_workorder" => $request_workorder, "approval_workorder" => $approval_workorder, "rejected_workoder" => $rejected_workoder, "total_request_workorder" => $total_request_workorder, 
			"total_request_tender" => $total_request_tender, "request_tender" => $request_tender, "approval_tender" => $approval_tender, "rejected_tender" => $rejected_tender, 
			"total_request_spk" => $total_request_spk, "request_spk" => $request_spk, "approval_spk" => $approval_spk, "rejected_spk" => $rejected_spk, 
			"total_request_budget_tahunan" => $total_request_budget_tahunan, "request_budget_tahunan" => $request_budget_tahunan, "approval_budget_tahunan" => $approval_budget_tahunan, "rejected_budget_tahunan" => $rejected_budget_tahunan, "total_request_rab" => $total_request_rab,  "approval_rab" => $approval_rab, "rejected_rab" => $rejected_rab, "request_rab" => $request_rab]); 
	}

	public function budget(Request $request){
		
		$budget = Budget::find($request->id);
		$project = $budget->project;
		$user = \Auth::user();
		$approval = $budget->approval;
		$effisiensi_netto = 0;
		if ( $budget->project->netto > 0 ){
			$effisiensi_netto = $budget->total_devcost / $budget->project->netto;
		}
		return view("project.user.budgets",compact("budget","project","user","approval","effisiensi_netto"));
	}

	public function budget_detail(Request $request){
		$budget = Budget::find($request->id);
		$approval = $budget->approval;
		$user = \Auth::user();
		$project = $budget->project_id;
		$effisiensi_netto = 0;
		if ( $budget->project->netto > 0 ){
			$effisiensi_netto = $budget->total_devcost / $budget->project->netto;
		}
		return view("project.user.budgets",compact("budget","project","user","approval","effisiensi_netto"));
		
	}

	public function budget_approval(Request $request){
		foreach ($request->budget_id as $key => $value) {
			# code...
			$approva_history_id = ApprovalHistory::where("document_id",$request->input('budget_id.'.$key.'.value'))->where("document_type","App\Budget")->where("user_id",$request->user_id)->get()->first();
			$approva_history = ApprovalHistory::find($approva_history_id->id);
			$approva_history->approval_action_id = $request->status;
			$approva_history->description = $request->input('description.'.$key.'.value');
			$status = $approva_history->save();

			$budget = Budget::find($request->input('budget_id.'.$key.'.value'));
			$highest = $budget->approval->histories->min("no_urut");
			if ( $approva_history->no_urut == $highest){		
				$approval_ac = Approval::find($budget->approval->id);
				$approval_ac->approval_action_id = $request->status;
				$approval_ac->save();
			}
		}

		if ( $status ){
			return response()->json( ["status" => "0"] );
		}else{
			return response()->json( ["status" => "1"] );
		}
	}

	public function budget_faskot(Request $request){
		$budget_id = Budget::find($request->budget_id);
		$user_id = $request->user_id;
		$status = $request->status;

		$document = $budget_id->approval->histories;
		$approval_id = $document->where("user_id",$user_id)->first();
		$highest = $budget_id->approval->histories->min("no_urut");

		if ( isset($approval_id->id)){
			$approval_history = ApprovalHistory::find($approval_id->id);
			$approval_history->approval_action_id = $status;
			$status = $approval_history->save();
			
			if ( $approval_history->no_urut == $highest){		
				$approval_ac = Approval::find($budget_id->approval->id);
				$approval_ac->approval_action_id = $request->status;
				$approval_ac->save();
			}

			if ( $status ){
				return response()->json( ["status" => "0"] );
			}else{
				return response()->json( ["status" => "1"] );
			}
		}else{
			return response()->json( ["status" => "1"] );
		}
		
	}

	public function workorder(Request $request){
		$user 	 = \Auth::user();
		$workorders = Workorder::find($request->id);
		$project = $workorders->project->first();
		return view("project.user.workoder",compact("project","user","workorders"));
	}

	public function workorder_detail( Request $request){
		$workorder = Workorder::find($request->id);
		$pekerjaan = $workorder->detail_pekerjaan;
		$project = $workorder->project;
		$user 	 = \Auth::user();
		$approval = $workorder->approval;
		$workorder_unit = $workorder->details->where("asset_type","App\Unit");
		$devcost = 0;
		
		foreach ($workorder_unit as $key => $value) {
			# code...
			$devcost = $devcost + $value->asset->templatepekerjaan->con_cost ;
		}

		return view("project.user.workoder_detail",compact("workorder","pekerjaan","project","user","workorder_unit","devcost","approval"));
	}

	public function workorder_approval(Request $request){
		$approval_id = Workorder::find($request->workorder_id)->approval;
		$approva_history_id = ApprovalHistory::where("approval_id",$approval_id->id)->where("user_id",$request->user_id)->get()->first();
		$approva_history = ApprovalHistory::find($approva_history_id->id);
		$approva_history->approval_action_id = $request->status;
		$approva_history->description = $request->description;
		$status = $approva_history->save();
		if ( $status ){
			return response()->json( ["status" => "0"] );
		}else{
			return response()->json( ["status" => "1"] );
		}
	}

	public function itemdetail(Request $request){
		$item = Itempekerjaan::find($request->parent_id);

		$itempekerjaan = Itempekerjaan::where("parent_id",$request->parent_id)->get();
		$html = "";
		$nilai = 0;
		foreach ( $itempekerjaan as $key => $value ){
			$detailitem = Itempekerjaan::find($value->id);
			$unitworkorder = WorkorderBudgetDetail::where("itempekerjaan_id",$value->id)->where("workorder_id",$request->workorder)->get()->first();
			if (isset($unitworkorder->volume)){
				$html .= "<tr>";
				$html .= "<td>".$detailitem->code."</td>";
				$html .= "<td>".$detailitem->name."</td>";
				$html .= "<td>".number_format($unitworkorder->volume)."</td>";
				$html .= "<td>".$unitworkorder->satuan."</td>";
				$html .= "<td>".number_format($unitworkorder->nilai)."</td>";
				$html .= "<td>".number_format($unitworkorder->nilai * $unitworkorder->volume)."</td>";
				$html .= "</tr>";
				$nilai = $nilai + ($unitworkorder->nilai * $unitworkorder->volume);
			}
		}
		$html .= "<tr>";
		$html .= "<td colspan='5' style='text-align:right;'><i>Total</i></td>";
		$html .= "<td><strong>".number_format($nilai)."</strong></td>";
		$html .= "</tr>";
		return response()->json( ["html" => $html, "coa" => $item->code, "dept" => $item->department->name, "names" => $item->name ] );
	}

	public function tender(Request $request){
		$project = Project::find($request->id);
		$tenders = $project->tenders->get();
		$user = \Auth::user();
		$request_tender = 0;
		$approval_tender = 0;
		$rejected_tender = 0;
		$request_tender_rekanan = 0;
		$approval_tender_rekanan = 0;
		$rejected_tender_rekanan = 0;

		foreach ( $tenders as $key2 => $value2 ) {
		# code...
		$request_tender = $request_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",1)->count();
		$approval_tender = $approval_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",6)->count();
		$rejected_tender = $rejected_tender + $value2->approval->histories->where("user_id",$user)->where("approval_action_id",7)->count();
		foreach ($value2->rekanans as $key3 => $value3 ) {
			if (isset($value3->approval)){
				$request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
				$approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
				$rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();

			}
		}
		$request_tender = $request_tender + $request_tender_rekanan;
		$approval_tender = $approval_tender + $approval_tender_rekanan;
		$rejected_tender = $rejected_tender + $rejected_tender_rekanan;

		}

		return view("project.user.tender",compact("project","tenders","user","request_tender"));
	}

	public function tender_detail(Request $request){
		$tender = Tender::find($request->id);
		$user = \Auth::user();
		$project = $tender->rab->workorder->project;
		$approval = $tender->approval;

		$request_tender_rekanan = 0;
		$approval_tender_rekanan = 0;
		$rejected_tender_rekanan = 0;
		$approval_id = "";
		foreach ($tender->rekanans as $key3 => $value3 ) {
			if (isset($value3->approval)){
				$request_tender_rekanan = $request_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
				$approval_tender_rekanan = $approval_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
				$rejected_tender_rekanan = $rejected_tender_rekanan + $value3->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();
				if ( $request_tender_rekanan > 0 ){
					$approval_id .= $value3->approval->id .",";
				}
			}

		}
		
		if ( $approval_id !== "" ){
			$approval_id = trim($approval_id,",");
		}
		return view("project.user.tender_detail",compact("project","tender","user","approval","request_tender_rekanan","approval_tender_rekanan","rejected_tender_rekanan","approval_id"));
	}

	public function tender_workorder_detail( Request $request){
		$workorder = Workorder::find($request->id);
		$pekerjaan = $workorder->detail_pekerjaan;
		$project = $workorder->project;
		$user 	 = \Auth::user();
		$tender  = Tender::find($request->tender);
		return view("project.user.tender_workorder_detail",compact("workorder","pekerjaan","project","user","tender"));
	}

	public function tender_penawaran(Request $request){
		$tender = Tender::find($request->tender_id);
		$rekanan = $tender->rekanans->where("rekanan_id",$request->rekanan_id)->first();
		$html = "";
		$html .= "<tr>";
		foreach ($rekanan->penawarans as $key => $value) {
			# code...
			$html .= "<td>".number_format($value->nilai)."</td>";
		}
		$html .= "</tr>";
		return response()->json( ["html" => $html] );
	}

	public function rekanan_approve(Request $request){
		$approval_value = trim($request->apporval_value,"==");
		$explode_value = explode("==", $approval_value);
		$highest = Approval::find($request->approval_id)->histories->min("no_urut");
		$user 	 = \Auth::user();
		for ( $i=0; $i < count($explode_value); $i++ ){
			$explode_detail = explode("<>",$explode_value[$i]);
			$approval = ApprovalHistory::where("approval_id",$explode_detail[0])->where("user_id",$user->id)->first();
			$approval_history = ApprovalHistory::find($approval->id);
			$approval_history->approval_action_id = $explode_detail[1];
			$approval_history->description = $request->input('description.'.$i.'.value');
			$approval_history->save();

			
		}

		return response()->json( ["status" => "0"] );
	}

	public function spk(Request $request){
		$project = Project::find($request->id);
		$user = \Auth::user();
		$spks = $project->spks;

		/*SPK*/
		$request_spk = 0;
		$approval_spk = 0;
		$rejected_spk = 0;

		foreach ($project->spks as $key4 => $value4) {
			# code...
			if (isset($value4->approval)){
				$request_spk = $request_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",1)->count();
				$approval_spk = $approval_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",6)->count();
				$rejected_spk = $rejected_spk + $value4->approval->histories->where("user_id",$user->id)->where("approval_action_id",7)->count();
			}
		}

		return view("project.user.spk",compact("project","user","spks","request_spk"));
	}

	public function spk_detail(Request $request){
		$user = \Auth::user();
		$spk  = Spk::find($request->id);
		$project = Project::find($spk->project_id);
		$approval = $spk->approval->histories->where("user_id",$user->id)->first();
		if ( $approval->approval_action_id == "1" ){
			$status = "";
		}else if ( $approval->approval_action_id == "6" ){
			$status = "<span class='badge bg-success' style='font-size:20px;'>Approve</span>";
		}else {
			$status = "<span class='badge bg-danger' style='font-size:20px;'>Reject</span>";
		}
		return view("project.user.spk_detail",compact("project","user","spk","status")); 
	}

	public function spk_approve(Request $request){
		$spk = Spk::find($request->spk_id);
		$approval = $spk->approval;
		$approva_history_id = ApprovalHistory::where("approval_id",$approval->id)->where("user_id",$request->user_id)->get()->first();
		$approval_history = ApprovalHistory::find($approva_history_id->id);
		$approval_history->approval_action_id = $request->approve;
		$approval_history->description = $request->description;
		$approval_history->save();
		
		$highest = $approval->histories->min("no_urut");
		if ( $approva_history_id->no_urut == $highest){		
			$approval_ac = Approval::find($approval->id);
			$approval_ac->approval_action_id = $request->approve;
			$approval_ac->save();
		}
		return response()->json( ["status" => "0"] );
	}

	public function vo(Request $request){
		$user = \Auth::user();
		$project = Project::find($request->id);
		return view("project.user.vo",compact("project","user"));
	}

	public function vo_detail(Request $request){
		$user = \Auth::user();
		$vo = Vo::find($request->id);
		$project = $vo->spk->project;
		return view("project.user.vo_detail",compact("project","user","vo"));
	}

	public function budget_unit(Request $request){
		$project_kawasan = ProjectKawasan::find($request->id);
		$project = Project::find($project_kawasan->project_id);
		$user = \Auth::user();
		$budgets = $project_kawasan->budgets->first();
		return view("project.user.budget_unit",compact("project_kawasan","project","user","budgets"));
	}

	public function detail_unit(Request $request){
		$blok = Blok::find($request->id);
		$template = $blok->template_pekerjaan;
		$user = \Auth::user();
		return view("project.user.budget_template",compact("template","user","blok"));
	}

	public function template_unit(Request $request){
		$template = Templatepekerjaan::find($request->id);
		$user = \Auth::user();
		$blok = Blok::find($request->blok);
		return view("project.user.template_pekerjaan",compact("template","user","blok"));
	}

	public function budget_tahunan(Request $request){
		//$project = Project::find($request->id);
		//$user = \Auth::user();
		//return view("project.user.budget_tahunan",compact("project","user"));
		$budget = BudgetTahunan::find($request->id);
		$project = $budget->project;
		$user = \Auth::user();
		$approval = $budget->approval;
		$effisiensi_netto = 0;
		if ( $budget->project->netto > 0 ){
			$effisiensi_netto = $budget->total_devcost / $budget->project->netto;
		}
		return view("project.user.budgets",compact("budget","project","user","approval","effisiensi_netto"));
	}

	public function budget_tahunan_approval(Request $request){
		$budget_id = BudgetTahunan::find($request->budget_id);
		$user_id = $request->user_id;
		$status = $request->status;

		$document = $budget_id->approval->histories;
		$approval_id = $document->where("user_id",$user_id)->first();
		if ( isset($approval_id->id)){
			$approval_history = ApprovalHistory::find($approval_id->id);
			$approval_history->approval_action_id = $status;
			$status = $approval_history->save();
			if ( $status ){
				return response()->json( ["status" => "0"] );
			}else{
				return response()->json( ["status" => "1"] );
			}
		}else{
			return response()->json( ["status" => "1"] );
		}
		
	}

	public function rab(Request $request){
		$project = Project::find($request->id);
		$rab = $project->rabs;
		$user = \Auth::user();
		return view("project.user.rab",compact("rab","project","user"));
	}

	public function rab_detail(Request $request){
		$rab = Rab::find($request->id);
		$user = \Auth::user();
		$approval = $rab->approval;
		return view("project.user.rab_detail",compact("rab","user","approval"));
	}

	public function rab_approval(Request $request){
		$rab = Rab::find($request->rab_id);
		$user_id = $request->user_id;
		$status = $request->status;

		$document = $rab->approval->histories;
		$approval_id = $document->where("user_id",$user_id)->first();
		if ( isset($approval_id->id)){
			$approval_history = ApprovalHistory::find($approval_id->id);
			$approval_history->approval_action_id = $status;
			$status = $approval_history->save();
			if ( $status ){
				return response()->json( ["status" => "0"] );
			}else{
				return response()->json( ["status" => "1"] );
			}
		}else{
			return response()->json( ["status" => "1"] );
		}
		
	}

	public function tender_menang(Request $request){
		$explode = explode(",",trim($request->list_rekanan_approval_id,","));

		for ( $i=0; $i < count($explode); $i++ ){
			if ( $explode[$i] == $request->rekanan_approval_id){
				$approval = Approval::find($request->rekanan_approval_id);
				$approval->approval_action_id = "6";
				$status = $approval->save();

				$approval_history = $approval->histories->where("user_id",$request->user_id)->first();				
				$approval_history_id = ApprovalHistory::find($approval_history->id);
				$approval_history_id->approval_action_id = $request->status;
				$approval_history_id->save();


				$highest = Approval::find($request->rekanan_approval_id)->histories->min("no_urut");
				if ( $approval->no_urut == $highest){
					$approval_ac = Approval::find($request->approval_id);
					$approval_ac->approval_action_id = $explode_detail[1];
					$approval_ac->save();
				}

			}else{
				$approval = Approval::find($explode[$i]);
				$approval->approval_action_id = "7";
				$status = $approval->save();

				$approval_history = $approval->histories->where("user_id",$request->user_id)->first();
				$approval_history_id = ApprovalHistory::find($approval_history->id);
				$approval_history_id->approval_action_id = "7";
				$approval_history_id->save();

				$highest = Approval::find($request->rekanan_approval_id)->histories->min("no_urut");
				if ( $approval->no_urut == $highest){
					$approval_ac = Approval::find($request->approval_id);
					$approval_ac->approval_action_id = $explode_detail[1];
					$approval_ac->save();
				}


			}
		}


		if ( $status ){
			return response()->json( ["status" => "0"] );
		}else{
			return response()->json( ["status" => "1"] );
		}
	}

	public function tender_rab_detail(Request $request){
		$rab = Rab::find($request->id);
		$user = \Auth::user();
		$tender = Tender::find($request->tender);
		return view("project.user.tender_rab_detail",compact("rab","user","tender"));
	}

	public function tender_approved(Request $request){
		$tender = Tender::find($request->tender_id);
		$status = $request->status;
		$user   = $request->user_id;
		$approval = $tender->approval;
		$highest = $approval->histories->min("no_urut");


		$approval_history = $approval->histories->where("user_id",$user)->first();
		$approval_history_id = ApprovalHistory::find($approval_history->id);
		$approval_history_id->approval_action_id = $status ;
		$status = $approval_history_id->save();

		if ( $approval_history->no_urut == $highest){		
			$approval_ac = Approval::find($approval->id);
			$approval_ac->approval_action_id = $request->status;
			$approval_ac->save();
		}


		$approval_value = trim($request->rekanan,"==");
		$explode_value = explode("==", $approval_value);
		for ( $i=0; $i < count($explode_value); $i++ ){
			$explode_detail = explode("<>",$explode_value[$i]);
			$approval = ApprovalHistory::where("approval_id",$explode_detail[0])->where("user_id",$user)->first();
			$approval_history = ApprovalHistory::find($approval->id);
			$approval_history->approval_action_id = $explode_detail[1];
			$approval_history->description = $request->input('description.'.$i.'.value');
			$approval_history->save();

			if ( $approval_history->no_urut == $highest){		
				$approval_ac = Approval::find($explode_detail[0]);
				$approval_ac->approval_action_id = $request->status;
				$approval_ac->save();
			}
			
		}
		
		if ( $status ){
			return response()->json( ["status" => "0"] );
		}else{
			return response()->json( ["status" => "1"] );
		}
	}

	public function budget_devcost(Request $request){
		$user = \Auth::user();
		$project = Project::find($request->id);
		$budgets = $project->budgets;
		$budget_project = 0;
		$budget_project_kawasan = 0;
		$total_budget = 0;
		$effisiensi_netto = 0;
		$approval = "";
		$total_devcost = 0;
		$total_concost = 0;
		$budget_total = $budgets->where("project_kawasan_id",null)->first();
			if ( isset($budget_total->id)){
			$budget = Budget::find($budget_total->id);
			$approval = Budget::find($budget_total->id)->approval;

			foreach ($budgets as $key => $value) {
				# code...
				if ( $value->project_kawasan_id == null ){
					$budget_project = $value->nilai;
					$total_devcost  = $total_devcost + $value->total_dev_cost;
				}else{
					$budget_project_kawasan = $value->total_dev_cost;
					$total_devcost  = $total_devcost + $value->total_dev_cost;
					$total_concost  = $total_concost + $value->total_con_cost;
				}

				$total_budget = $total_budget + $value->nilai;
			}

			if ( $project->netto > 0 ){
				$effisiensi_netto = $total_budget / $project->netto;
			}
			
		}
		return view("project.user.budget_devcost",compact("project","user","total_budget","budget_project","budget_project_kawasan","approval","budget","effisiensi_netto","total_devcost","total_concost"));
	}

	public function budget_concost(Request $request){
		$user = \Auth::user();
		$project = Project::find($request->id);
		$budgets = $project->budgets;
		$start = 0;
		foreach ($project->kawasans as $key => $value) {
			foreach ($value->units as $key2 => $value2 ) {
				$array[$start] = $value2->unit_type_id;
				$start++;
			}
		}

		$type = array_values(array_unique($array));
		return view("project.user.budget_concost",compact("project","user","budgets","type"));
	}

	public function department(Request $request){
		$project = $request->id;

	}

	public function approve_all(Request $request){
		$approval_list = explode("<>", trim($request->approval_list,"<>"));
		for ( $i=0; $i < count($approval_list); $i++ ){
			$approval = explode(",", $approval_list[$i]);

			$approval_history = ApprovalHistory::find($approval[1]);
			$approval_history->approval_action_id = $approval[0];
			$approval_history->save();

			$approvals = Approval::find($approval_history->approval_id);
			$highest  = $approvals->histories->min("no_urut");
			if ( $highest == $approval_history->no_urut ){
				$approvals->approval_action_id = $approval[0];
				$approvals->save();
			}
		}
		return response()->json( ["status" => "0"] );
	}

	public function tender_korespondensi(Request $request){
		$user = \Auth::user();
		$tender_korespondensi = TenderKorespondensi::find($request->id);
		$arrayKoresponend = array(
	        "udg" => "Undangan Penawaran dan Klarifikasi",
	        "sipp" => "Surat Instruksi Penunjukan Pemenang",
	        "pp" => "Surat Pemberitahuan Pemenang",
	        "sutk" => "Surat Ucapan Terima Kasih",
	        "spt" => "Surat Pembatalan Tender"
	    );
		$type = $arrayKoresponend[$tender_korespondensi->type];
		return view("project.user.tender_korespondensi",compact("tender_korespondensi","type","user"));
	}

	public function tender_korespondensi_approval(Request $request){
		$user = \Auth::user();
		$user_id = $request->user_id;
		$approval = $request->approval;
		$status = $request->status;

		$approval_history = ApprovalHistory::find($approval);
		$approval_history->approval_action_id = $status;
		$approval_history->save();

		$approvals = Approval::find($approval_history->approval_id);
		$highest  = $approvals->histories->min("no_urut");
		if ( $highest == $approval_history->no_urut ){
			$approvals->approval_action_id = $status;
			$approvals->save();
		}
		return response()->json( ["status" => "0"] );
	}

}

?>
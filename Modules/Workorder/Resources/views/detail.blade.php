<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
    <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Workorder</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">   

              <h3 class="box-title">Tambah Data Workorder</h3>           
              <form action="{{ url('/')}}/workorder/update" method="post" name="form1">
                {{ csrf_field() }}
              <input type="hidden" name="workorder_id" value="{{ $workorder->id }}">
              <input type="hidden" name="project_id" value="{{ $project->id }}">
              <div class="form-group">
                <label>No. Workorder</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $workorder->no }}" readonly>
              </div>  
              <div class="form-group">
                <label>Department In Charge</label>
                <select class="form-control" name="department_from" id="department_from">
                  <option value="">( pilih departemen ) </option>
                  @foreach ( $department as $key => $value )
                   
                      @if ( $value->id == $workorder->department_from )
                      <option value="{{ $value->id}}" selected>{{ $value->name }}</option>
                      @else
                       <option value="{{ $value->id}}">{{ $value->name }}</option>
                      @endif
                  @endforeach
                </select>
              </div>  
              <div class="form-group">
                <label>Department Support</label>
                <select class="form-control" name="department_to">
                  @foreach ( $department as $key => $value )
                   @if ( $value->id == $workorder->department_from )
                      <option value="{{ $value->id}}" selected>{{ $value->name }}</option>
                      @else                      
                      <option value="{{ $value->id}}">{{ $value->name }}</option>
                      @endif
                  @endforeach
                </select>
              </div>  
              <div class="form-group">
                <label>Nilai Workorder(Rp)</label>
                <h3><strong>{{ number_format($workorder->nilai) }}</strong></h3>
              </div>
                               
              <div class="box-footer">
                @if ( $workorder->approval == "" )
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-info" onclick="woapprove('{{ $workorder->id }}')">Request Approve</button>
                @else
                @php
                  $array = array (
                    "6" => array("label" => "Disetujui", "class" => "label label-success"),
                    "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                    "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
                  )
                @endphp
                <span class="{{ $array[$workorder->approval->approval_action_id]['class'] }}">{{ $array[$workorder->approval->approval_action_id]['label'] }}</span>
                <a href="{{ url('/')}}/workorder/approval_history/?id={{ $workorder->id}}" class="btn btn-primary">Histroy Approval</a>
                @endif
                <a href="{{ url('')}}/workorder" class="btn btn-warning">Kembali</a>
              </div>
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
             <div class="col-md-6">
              <h3>&nbsp;</h3>
              <div class="form-group">
                <label>Durasi Proses WO (Hari Kalender)</label>
                <input type="text" class="form-control" name="workorder_durasi" value="{{ $workorder->durasi }}" required>
              </div> 
              <div class="form-group">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="workorder_description" value="{{ $workorder->description }}">
              </div> 
              <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="workorder_name" value="{{ $workorder->name }}" required>
              </div>
            </div>
            </form>
            <!-- /.col -->
          </div>
          <div class="nav-tabs-custom">
              
              <ul class="nav nav-tabs">                
                <li class="active"><a href="#tab_3" data-toggle="tab">Item Pekerjaan</a></li>
                <li><a href="#tab_2" data-toggle="tab">Unit</a></li>
              </ul>
              <div class="tab-content">                
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_3">
                    @if ( $workorder->approval == "" )
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default">
                      Tambah Item Pekerjaan
                    </button>
                    @endif<br>
                    <table class="table table-bordered">
                     <thead class="head_table">
                       <tr>
                        <td>COA</td>
                        <td>Item Pekerjaan</td>
                        <td>No. Budget Tahunan</td>
                        <td>Total Budget Tahunan(Rp)</td>
                        <td>Volume</td>
                        <td>Satuan</td>
                        <td>Nilai(Rp)</td>
                        <td>Subtotal(Rp)</td>
                       </tr>
                     </thead>
                     <tbody id="detail_item">
                       @foreach ( $workorder->parent_id as $key => $value )
                       @if ( $value['subtotal'] != "0")
                        @if ( $value['total_budget'] == "0")
                          @php
                            $class = "background-color:grey;color:white;font-weight:bolder;";
                            $label = "Tidak ada di Budget Tahunan. Menunggu Approval";
                          @endphp
                        @else
                          @php
                            $class = "";
                            $label = number_format($value['total_budget']);
                          @endphp
                        @endif
                        <tr style="{{ $class }}">
                          <td>{{ $value['coa_code'] }}</td>
                          <td>{{ $value['item_name'] }}</td>
                          <td>{{ $value['budget_tahunan'] }}</td>
                          <td>{{ $label }}</td>
                          <td>{{ number_format($value['volume']) }}</td>
                          <td>{{ $value['satuan'] }}</td>
                          <td>{{ number_format($value['unitprice']) }}</td>
                          <td>{{ number_format($value['subtotal']) }}</td>
                       </tr>
                       @endif
                       @endforeach
                     </tbody>
                   </table> 
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  @if ( $workorder->approval == "" )
                    @if ( count($workorder->budget_parent) > 0 )
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                        Tambah Unit
                    </button>
                    @else
                    <h4>Silahkan pilih budget tahunan terlebih dahulu</h4>
                    @endif
                  @endif<br>
                  <table class="table table-bordered">
                     <thead class="head_table">
                       <tr>
                        <td>No.</td>
                        <td>Asset Type</td>
                        <td>Nama</td>
                        <td>Delete</td>
                       </tr>
                     </thead>
                     <tbody id="detail_item">
                       
                       @foreach ( $workorder->details as $key => $value )
                        <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>{{ str_replace("Modules\Project\Entities","",$value->asset_type) }}</td>
                          <td>{{ $value->asset->name or ''}}</td>
                          <td>
                            @if ( $workorder->approval == "" )
                            <button class="btn btn-danger" onclick="removeunitswo('{{ $value->id }}')">Delete</button>
                            @else
                            @php
                              $array = array (
                                "6" => array("label" => "Disetujui", "class" => "label label-success"),
                                "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                                "1" => array("label" => "Dalam Proses", "class" => "label label-warning")
                              )
                            @endphp
                            <span class="{{ $array[$workorder->approval->approval_action_id]['class'] }}">{{ $array[$workorder->approval->approval_action_id]['label'] }}</span>
                            @endif
                          </td>
                       </tr>
                       @endforeach
                     </tbody>
                   </table> 
                </div>
              </div>
              <!-- /.tab-content -->
            </div
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <form action="{{ url('/')}}/workorder/choose-budget" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label>Budget Tahunan</label>            
              {{ csrf_field() }}
            <input type="hidden" name="workoder_par_id" value="{{ $workorder->id}}">
            <select class="form-control" name="budget_tahunan" id="budget_tahunan">
              <option value="">( pilih budget tahunan)</option>
              @foreach ( $workorder->departmentFrom->budgets as $key => $value )
                @if ( $value->project_id == $project->id )
                  @foreach ( $value->budget_tahunans as $key2 => $value2 )
                    @if ( $value2->tahun_anggaran == date('Y') && $value2->nilai != "")
                    <option value="{{ $value2->id }}">{{ $value2->no }} ( {{ $value2->budget->kawasan->name or 'Fasilitas Kota'}} )</option>
                    @endif
                  @endforeach
                @endif
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <table id="tdsa">
              
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Pilih</button>
        </div>
      </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <div class="modal fade" id="modal-info">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <form action="{{ url('/')}}/workorder/save-units" method="post">
          {{ csrf_field() }}
        <input type="hidden" name="workorder_unit_id" value="{{ $workorder->id }}">
        <div class="modal-body">

          <div class="form-group" id="item_pekerjaan">
            <table class="table-bordered table">
              <thead class="head_table">
                <tr>
                  <td>No.</td>
                  <td>Unit Name</td>
                  <td>Set to WO</td>
                </tr>
              </thead>
              <tbody id="table_item">
                @php $start=0; @endphp
                @for ( $i=0; $i < count($workorder->budget_parent); $i++)
                  @if ( \Modules\Budget\Entities\BudgetTahunan::find($workorder->budget_parent[$i])->budget->kawasan == null )
                  <tr>
                    <td>{{ $start + 1 }}</td>                 
                    <td>{{ \Modules\Budget\Entities\BudgetTahunan::find($workorder->budget_parent[$i])->budget->project->name }}</td>
                    <td><input type="checkbox" name="asset[{{ $start}}]" value="{{ \Modules\Budget\Entities\BudgetTahunan::find($workorder->budget_parent[$i])->project->id }}"></td>
                  </tr>                  
                  @else
                  @php
                      $budgettahunan = \Modules\Budget\Entities\BudgetTahunan::find($workorder->budget_parent[$i]);
                  @endphp
                  <tr>
                    <td>{{ $start + 1 }}</td>                 
                    <td>{{ $budgettahunan->budget->kawasan->name }}</td>
                    <td><input type="checkbox" name="asset[{{ $start}}]" value="{{ $budgettahunan->budget->kawasan->id }}"></td>
                  </tr>
                  @php 
                    $units_list = \Modules\Project\Entities\ProjectKawasan::find($budgettahunan->budget->kawasan->id)->units;
                  @endphp

                  @foreach ( $units_list as $key3 => $value3 )                  
                  @php $start++; @endphp
                  <tr>
                    <td>{{ $start + 1 }}</td>                 
                    <td>{{ $value3->name }}</td>
                    <td><input type="checkbox" name="asset[{{ $start}}]" value="Unit_{{ $value3->id }}"></td>
                  </tr>
                  @php $start++; @endphp
                  @endforeach

                  @endif
                  @php $start++; @endphp
                @endfor
                
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("workorder::app")
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>

</body>
</html>

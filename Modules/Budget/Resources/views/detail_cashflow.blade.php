<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard </title>
  @include("master/header")
  <!-- Select2 -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="{{ url('/')}}/assets/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek <strong>{{ $budget->project->name }}</strong></h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12"><h3 class="box-title">Detail Data Budget Tahunan Proyek</h3></div>
            <div class="col-md-6">             
              <form action="{{ url('/')}}/budget/cashflow/update-cashflow" method="post" name="form1">
                {{ csrf_field() }}
                <input type="hidden" name="budget_id" id="budget_id" value="{{ $budget->id }}">
                <input type="hidden" name="budget_tahunan_id" id="budget_tahunan_id" value="{{ $budget_tahunan->id }}">
                <div class="form-group">
                  <label>No. Budget Global</label>
                  <input type="text" class="form-control" value="{{ $budget->no }}" readonly>
                </div>
                <div class="form-group">
                  <label>No. Budget</label>
                  <input type="text" class="form-control" value="{{ $budget_tahunan->no }}" readonly>
                </div>
                <div class="form-group">
                <label>Project / Kawasan</label>
                <input type="text" class="form-control" value="{{ $budget->project->name }} / {{ $budget->kawasan->name or ''}}" readonly>
              </div> 
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <a href="{{ url('/')}}/budget/cashflow/?id={{ $budget_tahunan->budget->id}}" class="btn btn-warning">Kembali</a>
                  @if ( $budget_tahunan->approval != "" )
                  <a class="btn btn-info" href="{{ url('/')}}/budget/cashflow/approval?id={{$budget_tahunan->id}}">Lihat History Approval</a><br>
                  @endif
                </div>      
             
            </div>
            <div class="col-md-6">

                <div class="form-group">
                  <label>Nilai(Rp)</label>
                  <input type="text" class="form-control" value="{{ number_format($budget->nilai) }}" readonly>
                </div> 
                <div class="form-group">
                  <label>Tahun Anggaran</label>
                  <select name="tahun_anggaran" class="form-control">
                    @for($i=$start_date; $i <= $end_date; $i++ )
                      @if ( $budget_tahunan->tahun_anggaran == $i )
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                      @else
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endif
                    @endfor
                  </select>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" name="description" id="description" class="form-control">
                </div>
            </div>
             </form>
            <!-- /.col -->
            <div class="col-md-12">
              <table class="table-bordered table">
                <thead class="head_table">
                  <tr>
                    <td>Uraian</td>
                    <td>Dev Cost</td>
                    <td>Con Cost</td>
                    <td>Subtotal</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>CarryOver</td>
                    <td style="text-align: right;">0</td>
                    <td style="text-align: right;">0</td>
                    <td>0</td>
                  </tr>
                  <tr>
                    <td> Budget Tahun Berjalan </td>
                    <td style="text-align: right;"> Rp. {{ number_format($budget_tahunan->total_dev_cost)}}</td>
                    <td style="text-align: right;"> Rp. {{ number_format($budget_tahunan->total_con_cost)}}</td>
                    <td style="text-align: right;"> Rp. {{ number_format($budget_tahunan->total_dev_cost + $budget_tahunan->total_con_cost) }}</td>
                  </tr>
                  <tr style="background-color: grey;color:white;font-weight: bolder;">
                    <td colspan="3" style="text-align: right">Total</td>
                    <td style="text-align: right;;color:white;font-weight: bolder;"> Rp. {{ number_format($budget_tahunan->nilai)}}</td>
                  </tr>
                </tbody>
              </table>
              
              <div class="nav-tabs-custom">
              
              <ul class="nav nav-tabs">                
                <li class="active"><a href="#tab_1" data-toggle="tab">Item Pekerjaan</a></li>
                <li><a href="#tab_2" data-toggle="tab">Bulanan</a></li>
                <li><a href="#tab_3" data-toggle="tab">Budget Carry Over</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                  @if ( $budget_tahunan->approval != "" )
                    @if ( $budget_tahunan->approval->approval_action_id == "6")
                      <span class="label-success">Approved</span>
                    @elseif ( $budget_tahunan->approval->approval_action_id == "7")
                      <span class="label-danger">Budget anda ditolak</span><br><br>                      
                    @endif

                  @else
                  <a class="btn btn-primary" href="{{ url('/')}}/budget/cashflow/newadd-item/?id={{ $budget_tahunan->id }}">Tambah Item Pekerjaan</a>
                  @endif
                  <table class="table" style="padding: 0" id="example3">
                    <thead class="head_table">
                      <tr>
                        <td>COA</td>
                        <td>Item Pekerjaan</td>
                        <td>Nilai(Rp)</td>
                        <td colspan="2">Perubahan Data</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ( $budget_tahunan->total_parent_item as $key => $value )
                      @if ( count(\Modules\Pekerjaan\Entities\Itempekerjaan::where("code",trim($value['code']))->get()) > 0 )                       
                        <tr>
                          <td>{{ $value['code'] }}</td>
                          <td>{{ \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value['code'])->get()->first()->name }}</td>
                          
                          <td>{{ number_format($value["nilai"]) }}</td>
                          <td>
                            @if ( $budget_tahunan->approval == "" )
                              @php $explodecode = explode(".",$value['code']); @endphp

                                  @if ( count($explodecode) > 0 )
                                    @php $code = $explodecode[0]; @endphp
                                  @else
                                    @php $code = $value['code']; @endphp
                                  @endif
                              @if ( $value["nilai"] == "0" )
                                <a href="{{ url('/')}}/budget/cashflow/add-item?id={{ $code }}&budget={{ $budget_tahunan->id }}" class="btn btn-warning">Create Cash Flow</a>
                              @else
                                <a href="{{ url('/')}}/budget/cashflow/add-item?id={{ $code }}&budget={{ $budget_tahunan->id }}" class="btn btn-warning">Edit Cash Flow</a>
                              @endif
                            @else
                              @if ( $budget_tahunan->approval != "" )
                                @if ( $budget_tahunan->approval->approval_action_id != "6 ")
                                  @php $explodecode = explode(".",$value['code']); @endphp
                                  @if ( count($explodecode) > 0 )
                                    @php $code = $explodecode[0]; @endphp
                                  @else
                                    @php $code = $value['code']; @endphp
                                  @endif
                                  @if ( $value["nilai"] == "0" )
                                    <a href="{{ url('/')}}/budget/cashflow/revisi-item?id={{ $code }}&budget={{ $budget_tahunan->id }}" class="btn btn-warning">Create Cash Flow</a>
                                  @else
                                    <a href="{{ url('/')}}/budget/cashflow/revisi-item?id={{ $code }}&budget={{ $budget_tahunan->id }}" class="btn btn-warning">Edit Cash Flow</a>
                                  @endif
                                @endif
                              @endif
                            @endif
                          </td>
                        </tr>
                      
                      @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  {{ csrf_field()}}
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                    Tambah Data
                  </button><br>
                  <table  class="table table-responsive table-bordered" style="padding: 0">
                    <thead class="head_table">
                      <tr>
                        <td>COA</td>
                        <td>Item Pekerjaan</td>
                        <td>Total Budget(Rp)</td>
                        <td>Total Bulanan(Rp)</td>
                        <td>Jan</td>
                        <td>Feb</td>
                        <td>Mar</td>
                        <td>Apr</td>
                        <td>Mei</td>
                        <td>Juni</td>
                        <td>Jul</td>
                        <td>Ags</td>
                        <td>Sept</td>
                        <td>Okt</td>
                        <td>Nov</td>
                        <td>Des</td>
                        <td>Perubahan Data</td>
                      </tr>
                    </thead>
                    <tbody>
                      @if ( $budget_tahunan->budget_monthly != "" )
                      @foreach ( $budget_tahunan->budget_monthly as $key9 => $value9 )
                      @if (  \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value9['code'])->count() > 0 )                       
                        <tr>
                          <input type="hidden" name="item_id[$key]" value="{{ $value9['code']}}">
                          <input type="hidden" id="monthly_id_{{ $value9['id']}}" value="{{ $value9['id']}}">
                          <td>{{ \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value9['code'])->first()->code }}</td>
                          <td>{{ \Modules\Pekerjaan\Entities\Itempekerjaan::where("code",$value9['code'])->first()->name }}</td>
                          <td>{{ number_format($budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume")) }}</td>
                          <td>{{ number_format((( $value9['januari'] + $value9['februari'] + $value9['maret'] + $value9['april'] + $value9['mei'] + $value9['juni'] + $value9['juli'] + $value9['agustus'] + $value9['september'] + $value9['oktober'] + $value9['november'] + $value9['desember'] )/100) * ( ($budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))))  }}</td>
                          <td>
                            <span id="label_januari_{{ $value9['id']}}">{{ number_format(($value9['januari']/100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="januari_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['januari'] }} ">
                          </td>
                          <td>
                            <span id="label_februari_{{ $value9['id']}}">{{ number_format(($value9['februari']/100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="februari_{{ $value9['id']}}" style="display: none;width: 80%;" value="{{ $value9['februari'] }}">
                          </td>
                          <td>
                            <span id="label_maret_{{ $value9['id']}}">{{ number_format(($value9['maret']/100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="maret_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['maret'] }} ">
                          </td>
                          <td>
                            <span id="label_april_{{ $value9['id']}}">{{ number_format(($value9['april'] / 100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="april_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['april'] }} ">
                          </td>
                          <td>
                            <span id="label_mei_{{ $value9['id']}}">{{ number_format(($value9['mei'] /100 ) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="mei_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['mei'] }} ">
                          </td>
                          <td>
                            <span id="label_juni_{{ $value9['id']}}">{{ number_format(($value9['juni'] /100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="juni_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['juni'] }} ">
                          </td>
                          <td>
                            <span id="label_juli_{{ $value9['id']}}">{{ number_format(($value9['juli'] /100)* ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="juli_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['juli'] }} ">
                          </td>
                          <td>
                            <span id="label_agustus_{{ $value9['id']}}">{{ number_format(($value9['agustus'] /100) *  ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="agustus_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['agustus'] }} ">
                          </td>
                          <td>
                            <span id="label_september_{{ $value9['id']}}">{{ number_format(($value9['september'] /100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="september_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['september'] }} ">
                          </td>
                          <td>
                            <span id="label_oktober_{{ $value9['id']}}">{{ number_format(($value9['oktober'] /100) *  ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="oktober_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['oktober'] }} ">
                          </td>
                          <td>
                            <span id="label_november_{{ $value9['id']}}">{{ number_format(($value9['november'] /100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="november_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['november'] }} ">
                          </td>
                          <td>
                            <span id="label_desember_{{ $value9['id']}}">{{ number_format(($value9['desember'] /100) * ( $budget_tahunan->total_volume($value9['code'],"nilai") * $budget_tahunan->total_volume($value9['code'],"volume"))) }}</span>
                            <input type="text" id="desember_{{ $value9['id']}}" style="display: none;width: 80%;" value=" {{ $value9['desember'] }} ">
                          </td>
                          <td>
                            <button class="btn btn-warning" id="btn_edit1_{{ $value9['id']}}" onclick="viewedit('{{ $value9['id']}}')">Edit</button>
                            <button class="btn btn-success" id="btn_edit2_{{ $value9['id']}}" onclick="saveedit('{{ $value9['id']}}')" style="display: none;">Edit</button>
                            <button class="btn btn-danger" onclick="removeedit('{{ $value9['id']}}')">Delete</button>
                          </td>
                        </tr>                      
                      @endif
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                 
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                  <form action="{{ url('/')}}/budget/save-carryover" method="post">
                  <input type="hidden" name="carryover_budget_id" value="{{ $budget_tahunan->id }}">
                  {{ csrf_field() }}
                  <table class="table table-bordered">
                    <thead class="head_table">
                      <tr>
                        <td>COA Pekerjaan</td>
                        <td>Item Pekerjaan</td>
                        <td>No. SPK</td>
                        <td>Nilai SPK</td>
                        <td>Terbayar</td>
                        <td>Sisa Terbayar</td>
                        <td>Set to Carry Over</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ( $array_cashflow as $key4 => $value4 )
                      <tr>
                        <td>{{ $value4['coa']}}</td>
                        <td>{{ $value4['pekerjaan']}}</td>
                        <td>{{ $value4['nospk']}}</td>
                        <td>{{ number_format($value4['nilaispk'])}}</td>
                        <td>{{ number_format($value4['bap'])}}</td>
                        <td>{{ number_format($value4['sisa'])}}</td>
                        <td>
                          @if ( count(\Modules\Budget\Entities\BudgetCarryOver::where("spk_id",$value4['id'])->get()) > 0 )
                          @php
                            $id = \Modules\Budget\Entities\BudgetCarryOver::where("spk_id",$value4['id'])->get()->first();
                          @endphp
                          <button onclick="removecarry('{{ $id->id}}')" class="btn btn-danger">Delete</button>
                          @else
                          <input type="checkbox" name="settospk[{{$key4}}]" value="{{ $value4['id']}}" value="{{ $value4['id'] }}">
                          @endif
                          
                        </td>
                      @endforeach
                    </tbody>
                  </table>
                  @if ( count($array_cashflow) > 0  )
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  @endif
                </form>
                </div>
              </div>
              <!-- /.tab-content -->
            </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Info Modal</h4>
              </div>
              <div class="modal-body">
                <form action="{{ url('/')}}/budget/cashflow/save-monthly" method="post" name="form1">
                  {{ csrf_field()}}
                  <input type="hidden" name="budget_tahunan_id" value="{{ $budget_tahunan->id }}">
                  <div class="form-group">
                                    <button type="submit" class="btn btn-info" id="btn_save_bln">Save changes</button>
                    <label>Item Pekerjaan</label>
                    <select class="form-control" id="item_id_monthly" name="item_id_monthly" required>
                      <option value="">(pilih item pekerjaan)</option>
                      @foreach ( $budget_tahunan->total_parent_item as $key2 => $value2 )
                        @if ( \Modules\Pekerjaan\Entities\Itempekerjaan::where('code',$value2['code'])->count() > 0  )                          
                          <option value="{{ \Modules\Pekerjaan\Entities\Itempekerjaan::where('code',$value2['code'])->first()->id }}"> {{ $value2['code'] }}.00.00 - {{ \Modules\Pekerjaan\Entities\Itempekerjaan::where('code',$value2['code'])->first()->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div> 
                  <div class="form-group">
                    <label>Nilai Budget(Rp)</label>
                      @foreach ( $budget_tahunan->total_parent_item as $key2 => $value2 )
                        @if ( \Modules\Pekerjaan\Entities\Itempekerjaan::where('code',$value2['code'])->count() > 0  )
                          <span style="display: none;" class="label_budget" id="label_budget_{{ \Modules\Pekerjaan\Entities\Itempekerjaan::where('code',$value2['code'])->first()->id }}" data-value="{{ $value2['nilai']}} "><br>
                          <strong>{{ number_format($value2['nilai'],2)}}</strong>
                          </span>
                        @endif
                      @endforeach<br>
                    <label>Sisa(Rp)</label><br>
                    <span id="sisa_budget"></span>
                  </div> 
                  <table style="width: 100%;" class="table">    
                      <tr class="head_table">
                        <td></td>
                        <td>% <span id="lbl_percent_text"></span></td>
                        <td><input type="hidden" id="total_sub"/> Rp. <span id="lbl_budget_text"></span></td>

                      </tr>                
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Januari</td>
                        <td><input type="text" name="januari" id="januari" style="width: 20%;" onKeyUp="countPercentage('januari')" value="0">%</td>
                        <td><span id="lbl_januari" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Februari</td>
                        <td><input type="text" name="februari" id="februari" style="width: 20%;" onKeyUp="countPercentage('februari')" value="0">%</td>
                        <td><span id="lbl_februari" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Maret</td>
                        <td><input type="text" name="maret" id="maret" style="width: 20%;" onKeyUp="countPercentage('maret')" value="0">%</td>
                        <td><span id="lbl_maret" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">April</td>
                        <td><input type="text" name="april" id="april" style="width: 20%;" onKeyUp="countPercentage('april')" value="0">%</td>
                        <td><span id="lbl_april" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Mei</td>
                        <td><input type="text" name="mei" id="mei" style="width: 20%;" onKeyUp="countPercentage('mei')" value="0">%</td>
                        <td><span id="lbl_mei" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Juni</td>
                        <td><input type="text" name="juni" id="juni" style="width: 20%;" onKeyUp="countPercentage('juni')" value="0">%</td>
                        <td><span id="lbl_juni" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Juli</td>
                        <td><input type="text" name="juli" id="juli" style="width: 20%;" onKeyUp="countPercentage('juli')" value="0">%</td>
                        <td><span id="lbl_juli" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Agustus</td>
                        <td><input type="text" name="agustus" id="agustus" style="width: 20%;" onKeyUp="countPercentage('agustus')" value="0">%</td>
                        <td><span id="lbl_agustus" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">September</td>
                        <td><input type="text" name="september" id="september" style="width: 20%;" onKeyUp="countPercentage('september')" value="0">%</td>
                        <td><span id="lbl_september" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Oktober</td>
                        <td><input type="text" name="oktober" id="oktober" style="width: 20%;" onKeyUp="countPercentage('oktober')" value="0">%</td>
                        <td><span id="lbl_oktober" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">November</td>
                        <td><input type="text" name="november" id="november" style="width: 20%;" onKeyUp="countPercentage('november')" value="0">%</td>
                        <td><span id="lbl_november" data-value="0"></span></td>
                      </tr>
                      <tr>
                        <td style="background-color: grey;color:white;font-weight: bolder;">Desember</td>
                        <td><input type="text" name="desember" id="desember" style="width: 20%;" onKeyUp="countPercentage('desember')" value="0">%</td>
                        <td><span id="lbl_desember" data-value="0"></span></td>
                      </tr>
                                          
                  </table>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button>

              </div>
               <div class="alert alert-warning alert-dismissible">                    
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Harap Input Budget Bulanan dengan percentase.
              </div>
            </div>
          </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
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
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("budget::app")
<script type="text/javascript">
  $("#item_id_monthly").change(function(){
    $(".label_budget").hide();
    $("#label_budget_" + $("#item_id_monthly").val()).show();
    $("#total_sub").val("0");
  });

  function viewedit(id){
    $("#label_januari_" + id).hide();
    $("#label_februari_" + id).hide();
    $("#label_maret_" + id).hide();
    $("#label_april_" + id).hide();
    $("#label_mei_" + id).hide();
    $("#label_juni_" + id).hide();
    $("#label_juli_" + id).hide();
    $("#label_agustus_" + id).hide();
    $("#label_september_" + id).hide();
    $("#label_oktober_" + id).hide();
    $("#label_november_" + id).hide();
    $("#label_desember_" + id).hide();
    $("#btn_edit1_" +id).hide();

    $("#januari_" + id).show();
    $("#februari_" + id).show();
    $("#maret_" + id).show();
    $("#april_" + id).show();
    $("#mei_" + id).show();
    $("#juni_" + id).show();
    $("#juli_" + id).show();
    $("#agustus_" + id).show();
    $("#september_" + id).show();
    $("#oktober_" + id).show();
    $("#november_" + id).show();
    $("#desember_" + id).show();
    $("#btn_edit2_" +id).show();
  }

  function saveedit(id){

  var request = $.ajax({
    url : "{{ url('/')}}/budget/cashflow/update-monthly",
    data : {
      id : $("#monthly_id_" + id).val(),
      jan : $("#januari_" + id).val(),
      feb : $("#februari_" + id).val(),
      mar : $("#maret_" + id).val(),
      apr : $("#april_" + id).val(),
      mei : $("#mei_" + id).val(),
      jun : $("#juni_" + id).val(),
      jul : $("#juli_" + id).val(),
      agu : $("#agustus_" + id).val(),
      sept : $("#september_" + id).val(),
      okt : $("#oktober_" + id).val(),
      nov : $("#november_" + id).val(),
      des : $("#desember_" + id).val()
    },
    type : "post",
    dataType : "json"
  });

  request.done(function(data){
    if ( data.status == "0"){
      alert("Data telah diganti");
    }

    window.location.reload();
  })
}

  function removeedit(id){
  if ( confirm("Apakah anda yakin ingin menghapus data ini ? ")){
    var request = $.ajax({
      url : "{{ url('/')}}/budget/cashflow/delete-monthly",
      data : {
        id : id 
      },
      type : "post",
      dataType : "json"
    });

    request.done(function(data){
      if ( data.status == "0"){
        alert("data telah dihapus");
      }
      window.location.reload();
    })

  }else{
    return false;
  }
}

function removecarry(id){
  if ( confirm("Apakah anda yakin ingin menghapus data ini ?")){
    var request = $.ajax({
      url : "{{ url('/')}}/budget/delete-carryover",
      data : {
        id : id 
      },
      type : "post",
      dataType : "json"
    });

    request.done(function(data){
      if ( data.status == "0"){
        alert("data telah dihapus");
      }
      window.location.reload();
    })
  }else{
    return false;
  }
}

function countPercentage(bln){
  //console.log(bln);
  var percent = parseInt($("#"+bln).val());
  var sub2 = parseInt($("#label_budget_" + $("#item_id_monthly").val()).attr("data-value"));
  var sub = percent * ( parseInt(sub2)) / 100;
  var total = parseInt($("#total_sub").val());


  /*if ( total > sub2 ){
    alert("Persentase Budget Bulanan sudah 100 %");
    $("#btn_save_bln").hide();
    
  }else{
    
  }*/

    if ( sub != "NaN"){    
      $("#lbl_"+bln).text(sub);
      $("#lbl_"+bln).attr("data-value",sub);
      $("#lbl_"+bln).number(true); 
      $("#sisa_budget").text(sub2 - total);   
      $("#sisa_budget").number(true);   
    }
  
    $("#total_sub").val( parseInt($("#lbl_januari").attr("data-value")) + parseInt($("#lbl_februari").attr("data-value")) + parseInt($("#lbl_maret").attr("data-value")) + parseInt($("#lbl_april").attr("data-value")) + parseInt($("#lbl_mei").attr("data-value")) + parseInt($("#lbl_juni").attr("data-value")) + parseInt($("#lbl_juli").attr("data-value")) + parseInt($("#lbl_agustus").attr("data-value")) + parseInt($("#lbl_september").attr("data-value")) + parseInt($("#lbl_oktober").attr("data-value")) + parseInt($("#lbl_november").attr("data-value")) + parseInt($("#lbl_desember").attr("data-value")) );
    $("#lbl_budget_text").text($("#total_sub").val());
    $("#lbl_budget_text").number(true);

    var totals = ( parseInt($("#januari").val()) + parseInt($("#februari").val()) + parseInt($("#maret").val()) + parseInt($("#april").val()) + parseInt($("#mei").val()) + parseInt($("#juni").val()) + parseInt($("#juli").val()) + parseInt($("#agustus").val()) + parseInt($("#september").val()) + parseInt($("#oktober").val()) + parseInt($("#november").val()) + parseInt($("#desember").val()) );
    //console.log(totals);
    if ( totals != "NaN"){
      $("#lbl_percent_text").text(totals);
    }

    if ( parseInt($("#lbl_percent_text").text()) > 100 ){
      alert("Persentase Budget Bulanan sudah 100 %");
      $("#btn_save_bln").attr("style","display:none");
      $("#lbl_percent_text").text(parseInt(totals) - $("#" + bln).val());
      $("#" + bln).val("0");
    }else{
      $("#btn_save_bln").show();
    }


}

</script>
</body>
</html>

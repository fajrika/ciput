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
      <h1>Data Kawasan</h1>

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">

        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">              
              <h3 class="box-title">Tambah Data Blok</h3>
              <form action="{{ url('/')}}/project/save-blok" method="post" name="form1">
                {{ csrf_field() }}
              <input type="hidden" name="project_id" name="project_id" value="{{ $projectkawasan->project->id }}">
              <input type="hidden" name="projectkawasan" name="projectkawasan" value="{{ $projectkawasan->id }}">
              <div class="form-group">
                <label>Kawasan</label>
                <input type="text" class="form-control" value="{{ $projectkawasan->name }}" autocomplete="off" readonly>
              </div>
              <div class="form-group">
                <label>Kode Blok</label>
                <input type="text" class="form-control" name="kode"  value="" autocomplete="off" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Nama Blok</label>
                <input type="text" class="form-control" name="name"  value="" autocomplete="off" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Luas Blok(m2)</label>
                <input type="text" class="form-control" name="luas" id="luas" autocomplete="off" autocomplete="off" required>
              </div>
              
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class='form-control' name="description" id="description" cols="45" rows="5" placeholder="Descriptions"></textarea>
              </div>     
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/')}}/project/bloks/?id={{ $projectkawasan->id }}" class="btn btn-warning">Kembali</a>
              </div>
              </form>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
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
<!-- Select2 -->
<script src="{{ url('/')}}/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{ url('/')}}/assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript">
  $(function () {
    $("#luas").number(true);
  });
</script>
@include("pt::app")
</body>
</html>

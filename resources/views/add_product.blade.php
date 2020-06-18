@extends('master')
@section('title', 'Fiyat Uzmanı | Ürün Ekle')
@section('html-header-title', 'Ürün Ekleme')
@section('html-header-bc-parent', 'Anasayfa')
@section('html-header-bc-active', 'Ürün Ekle')
@section('special-css')
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('html-content')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Ürün Ekle</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form">
            <div id="cardbody" class="card-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Ürün Adresi</label>
                <input class="form-control" id="product-url" placeholder="URL">
              </div>
              <div class="form-check">
                <label id="add-result"></label>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button onclick="return false" class="btn btn-primary" id="add-button">Ekle</button>
            </div>
          </form>
        </div>

      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
@stop

@section('special-js')
<script src="/js/jquery.loading.min.js"></script>
@stop

@section('js-content')
<script>

$("#add-button").click(function() {
  $('#cardbody').loading();
  axios.post('add', {
      productURL: $("#product-url").val()
    })
    .then(function (response) {
      $("#add-result").css("color", response.data.success == true ? "green" : "red");
      $("#add-result").text(response.data.message);
      $('#cardbody').loading("stop");
    })
    .catch(function (error) {
      alert("Birşeyler ters gitti. Server çuvalladı. " + error);
    });
});
</script>
@stop

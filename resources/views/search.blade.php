@extends('master')
@section('title', 'Fiyat Uzmanı | Arama')
@section('html-header-title', 'Ürün Arama')
@section('html-header-bc-parent', 'Anasayfa')
@section('html-header-bc-active', 'Arama')
@section('special-css')
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('html-content')

    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">

            <div class="card-body">
              <table id="product_search_results" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Provider</th>
                  <th>Last Price Date</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>

          </div>

        </div>

      </div>

      <div class="row">
        <div class="col-12">

          <div class="card text-center">
            <!-- /.card-header -->
            <div class="card-body">
              Aradığınız ürünü bulamadıysanız mı? Eklemek için <a href="product/add"> tıklayın </a>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
@stop

@section('special-js')
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/js/jquery.loading.min.js"></script>
@stop

@section('js-content')
<script>

$(function() {
  const params = new URLSearchParams(window.location.search);
  $("#search-content").val(params.get("query"));
  search();
});
</script>
@stop

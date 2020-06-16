@extends('master')
@section('title', 'Fiyat Uzmanı | Anasayfa')
@section('html-header-title', 'Dashboard')
@section('html-header-bc-parent', 'Anasayfa')
@section('html-header-bc-active', 'Anasayfa')

@section('html-content')
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title">Son Eklenen Ürünler</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
              <thead>
              <tr>
                <th>Ürün</th>
                <th>Sağlayıcı</th>
                <th>Eklenme Tarihi</th>
              </tr>
              </thead>
              <tbody id="products-tbl">

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">Son Yapılan Aramalar</h3>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                  <th>Ürün</th>
                  <th>Sağlayıcı</th>
                  <th>Eklenme Tarihi</th>
                </tr>
                </thead>
                <tbody id="products-tbl">

                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</div>
@stop

@section('special-js')
<script src="/plugins/apexcharts/apexcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- OPTIONAL SCRIPTS -->
@stop
@section('js-content')
<script>

  $(function () {

    var top10_products = [];
    axios.get("{{ route('product.latest_10', null, false) }}")
		.then(function (response) {
			response.data.forEach(function(row){
        console.log("push");
				top10_products.push({'title': row.title, 'provider': row.provider, 'created_at': row.created_at, 'id': row.id});
			});

      console.log(top10_products);
      top10_products.forEach(function(row){
        var str = `<tr><td><img src="/img/default-150x150.png" alt="${row.title}" class="img-circle img-size-32 mr-2"><a href="/_price/${row.id}">${row.title}</a></td><td>${row.provider}</td><td>${row.created_at}</td></tr>`;
        $("#products-tbl").append(str);
      });

		});



    //$("#products-tbl").append();
    var url = "{{ route('price.get_with_id', $productId, false) }}";
    var price_data = [];
    var name = "{{ $title }}";



	axios.get(url)
		.then(function (response) {
			response.data.forEach(function(row){
				price_data.push({x: row.pricedate, y: row.price});
			});
			create_chart(price_data, name);
		});
  })

  function create_chart(chart_data, name){
		var options = {
          series: [{
          name: name,
          data: chart_data
        }],
          chart: {
          type: 'area',
          stacked: false,
          height: 350,
          zoom: {
            type: 'x',
            enabled: true,
            autoScaleYaxis: true
          },
          toolbar: {
            autoSelected: 'zoom'
          }
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: name,
          align: 'left'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        yaxis: {
          title: {
            text: 'TL'
          },
        },
        xaxis: {
          type: 'datetime',
          labels: {
            format: 'dd/MM/yyyy',
            datetimeUTC: false,
          }
        },
        tooltip: {
          shared: false,
          x: {
				format: "dd/MM/yyyy HH:mm",
          },
		  y: {
			formatter: function(value) { return value + " TL" }
		  }
        }
        };

        var chart = new ApexCharts(document.querySelector("#lineChart"), options);
        chart.render();
	}
</script>
@stop
</body>
</html>

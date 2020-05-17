@extends('master')

@section('title', 'Fiyat Uzmanı | ' . $title)
@section('html-header-title', $title)
@section('html-header-bc-parent', 'Anasayfa')
@section('html-header-bc-active', 'Ürün Fiyat')

@section('special-css')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="/plugins/apexcharts/styles.css" rel="stylesheet" />
@stop

@section('html-content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
            
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Line Chart</h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <div id="lineChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%;"></div>
                </div>
              </div>
              
            </div>
            

          </div>
          
        </div>
        
      </div>
    </section>
@stop

@section('special-js')
<script src="/plugins/apexcharts/apexcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stop

@section('js-content')
<script>

  $(function () {
    var url = "{{ route('price.get_with_id', $productId, false) }}";
    console.log( url );
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

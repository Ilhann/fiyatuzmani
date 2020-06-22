<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>@yield('title', 'Fiyat Uzmanı')</title>

  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

  @yield('special-css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/" class="nav-link"><img style="width: 200px; margin-top: -13px;" src="/img/fiyatuzmani.png"></a>
      </li>
    </ul>

    <form id="search-form" class="form-inline ml-3" action="/search" method="get">
      <div class="input-group input-group-sm">
        <input id="search-content" name="query" class="form-control form-control-navbar" type="search" placeholder="Ara" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit" id="search-form-button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </nav>

  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@yield('html-header-title', 'Fiyat Uzmanı')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">@yield('html-header-bc-parent', 'Home')</a></li>
              <li class="breadcrumb-item active">@yield('html-header-bc-active', 'Page')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>



    @yield('html-content')

  </div>



  <aside class="control-sidebar control-sidebar-dark">

    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>



  <footer class="main-footer">

    <div class="float-right d-none d-sm-inline">
      #fiyatuzmanı, #fiyat takibi, #grafik, #izleme, #indirimler
    </div>

    <strong>FiyatUzmani &copy; 2020.</strong> Contact: <a href="mailto:info@fiyatuzmani.com">info@fiyatuzmani.com</a>
  </footer>
</div>





<script src="/plugins/jquery/jquery.min.js"></script>

<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="/js/adminlte.min.js"></script>
<script src="/js/axios.min.js"></script>
@yield('special-js')

<script>

  $(function() {
    if(window.location.pathname === "/search"){
      $("#search-form-button").click(function() {
        search();
      });
    }

  });

  $("#search-form").submit(function(e){
    if(window.location.pathname === "/search"){
      e.preventDefault();
      return false;
    }
  });

        Storage.prototype.setObj = function(key, obj) {
            return this.setItem(key, JSON.stringify(obj))
        }
        Storage.prototype.getObj = function(key) {
            return JSON.parse(this.getItem(key))
        }

  function search(e){
    var search_string = $("#search-content").val();

    if(search_string === "") return;

     if (localStorage.getObj("searches") === null) {
        localStorage.setObj("searches", [search_string]);
      }else{
        var searches = [];
        searches = localStorage.getObj("searches");
        if(!searches.includes(search_string)){
            searches.push(search_string);
            localStorage.setObj("searches", searches);
        }
      }

    $('#product_search_results').loading();
    axios.get("/search/product?query=" + search_string)
      .then(function (response) {
        data = [];
        response.data.forEach(function(row){
          title_html = "<a href='_price/" + row.id + "'>" + row.title + "</a>";
          data.push([title_html, row.provider, row.last_receive]);
        });
        draw_datatable(data);
      });
    }

  function draw_datatable(data){
    $('#product_search_results').loading('stop');
    $('#product_search_results').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      destroy: true,
      data: data,
    });
  }

</script>
@yield('js-content')

@if (App::environment() == 'production')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-166665626-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-166665626-1');
</script>
<script data-ad-client="ca-pub-6525252529923961" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endif
</body>
</html>

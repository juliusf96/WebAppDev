<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('wp.css')}}">
  </head>
  
  <body> 
  <header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="{{url("/")}}">@yield('title')</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
      <li class="nav-item">
          <a class="nav-link" href="{{url("/")}}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url("watches/makes")}}">Makes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url("add_watch")}}">Add New Watch</a>
        </li>
      </ul>
      <form class="form-inline mt-2 mt-md-0" method="get" action="{{url("search_action")}}">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="query">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
</header>


    @yield('content')
  </body>
</html>
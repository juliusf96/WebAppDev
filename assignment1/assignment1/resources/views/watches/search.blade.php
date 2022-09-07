@extends('layouts.master')

@section('title')
  Search Result
@endsection

@section('content')
  <h1>Search Result</h1>

  @if ($watches)
    <ul class="mainlist">
      @foreach($watches as $watch)
        <li><a href="{{url("watch_details/$watch->id")}}"><?= htmlspecialchars($watch->make) ?> <?= htmlspecialchars($watch->model) ?></a></li> 
      @endforeach
    </ul>
    @else
      <p>No matches found.</p>
  @endif
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

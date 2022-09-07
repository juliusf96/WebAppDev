@extends('layouts.master')

@section('title')
  Makes List
@endsection

@section('content')
  <h1>Makes</h1>
  @if ($makes)
    <ul class="mainlist">
      @foreach($makes as $make)
        <li><a href="{{url("make/$make->make")}}"><?= htmlspecialchars($make->make) ?></a></li>
      @endforeach
    </ul>
    @else
      No make(es) found.
  @endif
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

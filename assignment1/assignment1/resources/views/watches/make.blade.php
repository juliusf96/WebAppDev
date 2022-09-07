@extends('layouts.master')

@section('title')
  <?= htmlspecialchars($make) ?>
@endsection

@section('content')
  <h1><?= htmlspecialchars($make) ?></h1>
  @if ($watches)
    <ul class="mainlist">
      @foreach($watches as $watch)
        <li><a href="{{url("watch_details/$watch->id")}}"><?= htmlspecialchars($watch->make) ?> <?= htmlspecialchars($watch->model) ?></a></li>
      @endforeach
    </ul>
    @else
      No watch(es) found.
  @endif
  <p><a href="{{url("add_watch")}}">Add a new watch</a></p>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection
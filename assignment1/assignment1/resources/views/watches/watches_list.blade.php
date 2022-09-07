@extends('layouts.master')

@section('title')
  Watches List
@endsection

@section('content')
  <h1>Watches</h1>
  <form method="get" action="{{url("/")}}">
    {{csrf_field()}}
    <div class="sort">
      <div class="sort-subsection">
        <input type="checkbox" name="sortByRating" value='true'>
        <label>Sort by average rating</label><br>
      </div>
      <div class="sort-subsection">
        <input type="checkbox" name="sortBySentiment" value='true'>
        <label>Sort by sentiment score</label><br>
      </div>
      <div class="sort-subsection">
        <input type="checkbox" name="asc" value='true'>
        <label>Sort in ascending order</label><br>
      </div>
    </div>
    <input type="submit" value="Sort">
  </form>

  @if ($watches)
    <ul class="mainlist">
      @foreach($watches as $watch)
        <li>@if($sortByRating) @if(!$watch->avgRating) No ratings @else {{$watch->avgRating}}/5 @endif @endif
          @if($sortBySentiment) Sentiment score:{{$watch->avgSentiment}} @endif
          <a href="{{url("watch_details/$watch->id")}}"> <?= htmlspecialchars($watch->make) ?> <?= htmlspecialchars($watch->model) ?></a></li> 
      @endforeach
    </ul>
    @else
      No watch(es) found.
  @endif
  <p><a href="{{url("add_watch")}}">Add a new watch</a></p>
@endsection

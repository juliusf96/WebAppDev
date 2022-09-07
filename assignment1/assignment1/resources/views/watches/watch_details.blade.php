@extends('layouts.master')

@section('title')
  Watch Details
@endsection

@section('content')
  <h1><?= htmlspecialchars($watch->model) ?></h1>
  <h4><?= htmlspecialchars($watch->make) ?></h4>
  <div class="watch-info-section">
    <h6 class="sub-heading">Description:</h6>
    <p><?= htmlspecialchars($watch->details) ?></p>
  </div>

  <div class="review-section">
    <h3>Reviews ({{$count}})</h3>
    @if ($reviews)
      @foreach($reviews as $review)
        <div class="review">
          <h6 class="sub-heading">{{$review->rating}}/5   <?= htmlspecialchars($review->user) ?></h6>
          <p class="review-text"><?= htmlspecialchars($review->reviewText) ?></p>
          <p class="review-edit-delete"><a href="{{url("edit_review/$review->id")}}">Edit review</a> | 
          <a href="{{url("delete_review/$review->id")}}">Delete review</a></p>
        </div>
      @endforeach
    @else
      <p>No review(s) found.</p>
    @endif
  </div>

  <p><a href="{{url("add_review/$watch->id")}}">Write a review for this watch</a> | 
  <a href="{{url("update_watch/$watch->id")}}">Update this watch</a> | 
  <a href="{{url("delete_watch/$watch->id")}}">Delete this watch</a></p>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

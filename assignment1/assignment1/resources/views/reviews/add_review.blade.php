@extends('layouts.master')

@section('title')
  Write a Review
@endsection

@section('content')
    <h1>Write a Review for <?= htmlspecialchars($watch->make) ?> <?= htmlspecialchars($watch->model) ?></h1> 
  <form method="post" action="{{url("add_review_action")}}">
    {{csrf_field()}}
    <input type='hidden' name='id' value='{{$watch->id}}'>
    <label>Username:</label><br>
    <input type="text" name="user"><br><br>
    <label>Rating:</label><br>
    1<input type="radio" name="rating" value="1">
    <input type="radio" name="rating" value="2">
    <input type="radio" name="rating" value="3">
    <input type="radio" name="rating" value="4">
    <input type="radio" name="rating" value="5">5<br><br>
    <label>Review text:</label><br>
    <textarea type='text' name='reviewText'></textarea><br>
    <input type="submit" value="Submit Review">
  </form>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

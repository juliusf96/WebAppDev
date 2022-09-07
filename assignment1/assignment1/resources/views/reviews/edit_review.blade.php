@extends('layouts.master')

@section('title')
  Edit Review
@endsection

@section('content')
    <h1>Edit Review</h1> 
  <form method="post" action="{{url("edit_review_action")}}">
    {{csrf_field()}}
    <input type='hidden' name='reviewId' value='{{$review->id}}'>
    <label>Username:</label><br>
    <input type="text" name="user" value="{{$review->user}}"><br><br>
    <label>Rating:</label><br>
    1<input type="radio" name="rating" value="1">
    <input type="radio" name="rating" value="2">
    <input type="radio" name="rating" value="3">
    <input type="radio" name="rating" value="4">
    <input type="radio" name="rating" value="5">5<br><br>
    <label>Review text:</label><br>
    <textarea type='text' name='reviewText'>{{$review->reviewText}}</textarea><br>
    <input type="submit" value="Submit Changes">
  </form>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection
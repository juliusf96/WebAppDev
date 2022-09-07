@extends('layouts.master')

@section('title')
  Add Watch
@endsection

@section('content')
    <h1>Add New Watch</h1> 
  <form method="post" action="{{url("add_watch_action")}}">
  {{csrf_field()}}
    <label>Make:</label><br>
    <input type="text" name="make"><br><br>
    <label>Model:</label><br>
    <input type="text" name="model"><br><br>
    <label>Details:</label><br>
    <textarea type='text' name='details'></textarea><br>
    <input type="submit" value="Add Watch">
  </form>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

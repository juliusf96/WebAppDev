@extends('layouts.master')

@section('title')
  Update Watch
@endsection

@section('content')
    <h1>Update Watch</h1> 
  <form method="post" action="{{url("update_watch_action")}}">
  {{csrf_field()}}
    <input type='hidden' name='id' value='{{$watch->id}}'>
    <label>Make:</label><br>
    <input type="text" name="make" value='{{$watch->make}}'><br><br>
    <label>Model:</label><br>
    <input type="text" name="model" value='{{$watch->model}}'><br><br>
    <label>Details:</label><br>
    <textarea type='text' name='details'>{{$watch->details}}</textarea><br>
    <input type="submit" value="Update Watch">
  </form>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection
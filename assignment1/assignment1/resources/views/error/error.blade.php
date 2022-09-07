@extends('layouts.master')

@section('title')
  Error
@endsection

@section('content')
  <h1>Error</h1>
    <p>{{$errorMessage}}</p>
  <p><a href="{{url("/")}}">Home</a></p>
@endsection

@extends('layouts.layout')

@section('content')
    <br><br>
    <h1>{{auth()->user()->username}}</h1>
    <br><br>
@endsection
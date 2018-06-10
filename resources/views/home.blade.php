@extends('layouts.layout')

@section('title')
    Home
@endsection

@section('content')
    <br><br>
    <h1>{{auth()->user()->username}}</h1>
    <br><br>
@endsection
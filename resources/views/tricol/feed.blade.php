@extends('tricol.layouts.mainlayout')

@section('title')
    Feed | {{auth()->user()->username}}
@endsection

@section('script_footer')
    @parent
    @include('tricol.elements.amplitudeinitializer')
@endsection


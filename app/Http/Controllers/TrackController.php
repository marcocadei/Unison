<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackController extends Controller
{
//    public function __construct(){
//        $this->middleware('auth');
//    }


    public function upload(){
        return view('tracks.upload');
    }
}

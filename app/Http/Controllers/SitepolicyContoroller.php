<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitepolicyContoroller extends Controller
{
    public function index(){
      return view('sitepolicy');
    }
}

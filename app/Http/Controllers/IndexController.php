<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use Auth;

class indexController extends Controller
{
    public function index(){
      $history = History::
                 orderBy('updated_at', 'desc')
                 ->take(10)
                 ->get();
      return view('index', [ 
          'history' => $history,
          ]);
    }
    
    public function logout(){
      if(!Auth::check()){
        $error = "ログインしてません。";
        return redirect('/')->with('error', $error);
      }
      Auth::logout();
      return redirect('/');
    }
}

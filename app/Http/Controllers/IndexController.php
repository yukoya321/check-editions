<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\User;
use Auth;

class indexController extends Controller
{
    public function index(){
      $user_histories = null;
      $histories = History::
                 orderBy('updated_at', 'desc')
                 ->take(10)
                 ->get();
      if(Auth::check()){
        $user_histories = User::
                 find(Auth::id())
                 ->histories()
                 ->orderBy('history_id', 'desc')
                 ->take(10)
                 ->get();
        //dd($user_history);
      }
      return view('index', [ 
          'histories' => $histories,
          'user_histories' => $user_histories,
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
    
    public function delete($id){
      if(!Auth::check()){
        $error = "ログインしてません。";
        return redirect('/')->with('error', $error);
      }
      $user = User::find(Auth::id());
      $user->histories()->detach($id);
      return redirect('/');
    }
}

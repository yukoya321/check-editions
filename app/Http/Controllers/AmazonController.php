<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class AmazonController extends Controller
{
    public function index()
    {
        return Socialite::driver('amazon')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('amazon')->user();
        dd($user);
    }
}
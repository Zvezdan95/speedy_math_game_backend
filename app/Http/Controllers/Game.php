<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Game extends Controller
{
    public function index(){
        return view('game', []);
    }
    public function scoreBoard(){
        return view('score_board', []);
    }
}

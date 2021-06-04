<?php

namespace App\Http\Controllers;

use App\HighScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HighScoreController extends Controller
{
    public function createHighScore(Request $request)
    {
        $score = HighScore::create($request->all());
        return response()->json($score);
    }

    public function scoreBoardEasy()
    {
        $score = DB::table('high_scores')
            ->select('name', 'score', 'difficulty')
            ->whereDifficulty('easy')
            ->get()
            ->sortByDesc('score')
            ->flatten()
            ->chunk(10)->first();

        return response()->json($score);
    }
    public function scoreBoardMedium()
    {
        $score = DB::table('high_scores')
            ->select('name', 'score', 'difficulty')
            ->whereDifficulty('medium')
            ->get()
            ->sortByDesc('score')
            ->flatten()
            ->chunk(10)->first();

        return response()->json($score);
    }

    public function scoreBoardHard()
    {
        $score = DB::table('high_scores')
            ->select('name', 'score', 'difficulty')
            ->whereDifficulty('hard')
            ->get()
            ->sortByDesc('score')
            ->flatten()
            ->chunk(10)->first();

        return response()->json($score);
    }

    public function scoreBoardExtreme()
    {
        $score = DB::table('high_scores')
            ->select('name', 'score', 'difficulty')
            ->whereDifficulty('extreme')
            ->get()
            ->sortByDesc('score')
            ->flatten()
            ->chunk(10)->first();

        return response()->json($score);
    }

    public function saveScore(Request $request){
        $high_score = HighScore::create([
            'score'      => $request->score,
            'name'       => $request->user_name,
            'difficulty' => $request->difficulty_level
        ]);

        return response()->json($high_score);
    }
}

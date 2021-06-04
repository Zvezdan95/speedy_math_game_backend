<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('game', 'Game@index')->name('game');
Route::get('score_board', 'Game@scoreBoard')->name('score_board');
Route::post('save_score', 'HighScoreController@saveScore')->name('game');
Route::get('score_board_easy', 'HighScoreController@scoreBoardEasy')->name('score_board_easy');
Route::get('score_board_medium', 'HighScoreController@scoreBoardMedium')->name('score_board_medium');
Route::get('score_board_hard', 'HighScoreController@scoreBoardHard')->name('score_board_hard');
Route::get('score_board_extreme', 'HighScoreController@scoreBoardExtreme')->name('score_board_hard');

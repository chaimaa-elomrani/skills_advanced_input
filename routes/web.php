<?php

use App\Http\Controllers\SkillsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/skills', [SkillsController::class, 'index'])->name('skills.index');
Route::post('/save-skills', 'App\Http\Controllers\SkillsController@store')->name('skills.store');
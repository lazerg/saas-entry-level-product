<?php

use App\Http\Controllers\Step1Controller;
use App\Http\Controllers\Step2Controller;

Route::post('step1', Step1Controller::class)->name('step1');
Route::post('step2', Step2Controller::class)->name('step2');

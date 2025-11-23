<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;

Route::get('/slider-items', [\App\Http\Controllers\SliderController::class, 'index']);
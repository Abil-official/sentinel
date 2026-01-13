<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('sentinel')->get('/debug', fn (Request $request) => phpinfo());

Route::get('/', function () {
    return view('welcome');
});

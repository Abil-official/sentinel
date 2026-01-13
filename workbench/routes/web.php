<?php

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\IpUtils;

TrustProxies::at('*');

Route::middleware('sentinel')->get('/debug', fn (Request $request) => phpinfo());

Route::get('/', function () {
    return view('welcome');
});

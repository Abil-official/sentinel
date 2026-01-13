<?php

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\IpUtils;

TrustProxies::at('*');

Route::get('/debug', fn (Request $request) => dd([
    'Request::ip()' => $request->ip(),
    'Request::ips()' => $request->ips(),
    'IpUtils::isPrivateIp(Request::ip())' => IpUtils::isPrivateIp($request->ip()),
    'Request::isFromTrustedProxy()' => $request->isFromTrustedProxy(),
    'headers' => $request->headers,
    'header:ngrok-req-id' => $request->header('Ngrok-Req-Id'),
    'SERVERS' => $_SERVER,
    'get_defined_constants' => get_defined_constants(),
]));

Route::get('/', function () {
    return view('welcome');
});

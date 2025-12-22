<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/widget.js', function () {
    $js = view('widget-js')->render();
    return response($js)
        ->header('Content-Type', 'application/javascript; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600')
        ->header('Access-Control-Allow-Origin', '*');
});

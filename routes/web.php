<?php

use Illuminate\Support\Facades\Route;
use App\Services\BBCNewsService;

Route::get('/', function () {
 $service = new BBCNewsService();
 return response()->json($service->constructNewsData());
   
});

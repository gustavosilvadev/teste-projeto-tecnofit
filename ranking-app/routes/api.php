<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalRecordContoller;

Route::get('ranking/', [PersonalRecordContoller::class, 'exibirRanking']);

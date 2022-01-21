<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/{version}/github/{secret}/{repo}', [\App\Http\Controllers\GithubController::class, 'index'])
    ->setWheres(['version'=>'v1','secret'=>config()->get('github.GITHUB_TOKEN'), 'repo'=>'repository']);


<?php

namespace App\Http\Controllers;

use Avxman\Github\Facades\GithubFacade;
use Illuminate\Support\Facades\Response;

class GithubController extends Controller
{

    public function index(){
        GithubFacade::instance();
        return Response::json(['status'=>true, 'message'=>['Комманда обработана :)']]);
    }

}

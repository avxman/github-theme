<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class GithubController extends Controller
{

    public function index(){
        return Response::json(['status'=>true, 'message'=>['It\'s!!!']]);
    }

}

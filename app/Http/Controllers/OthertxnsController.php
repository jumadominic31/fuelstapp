<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Othertxn;
use App\Eoday;
use Validator;

class OthertxnsController extends Controller
{
    public function create()
    {
        return view('othertxns.create');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
use Validator;
use PDF;

class PDFController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->orderBy('created_at','asc')->get();
        $pdf = PDF::loadView('pdf.index',['stations'=> $stations]);
        return $pdf->stream('stations.pdf');
        //return View('pdf.index', ['stations'=> $stations]);
    }
}

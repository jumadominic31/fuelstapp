<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rate;
use App\Station;
use Auth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class RatesController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $rates = Rate::where('companyid', '=', $companyid)->orderBy('created_at','desc')->paginate(7);
        return View('rates.index',['rates'=> $rates]);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id');
        return view('rates.create', ['stations' => $stations]);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'stationid' => 'required',
            'start_rate_date' => 'required',
            'end_rate_date' => 'required',
            'stationid' => 'required',
            'fueltype'  => 'required',
            'buyprice'  => 'required|numeric',
            'sellprice' => 'required|numeric'
        ]);

        $user = Auth::user();
        $user_id = $user->id;
                
        $rate = new Rate;
        $rate->companyid = $companyid;
        $rate->start_rate_date = $request->input('start_rate_date');
        $rate->end_rate_date = $request->input('end_rate_date');
        $rate->stationid  = $request->input('stationid');
        $rate->fueltype  = $request->input('fueltype');
        $rate->buyprice  = $request->input('buyprice');
        $rate->sellprice = $request->input('sellprice');
        $rate->updated_by = $user_id;
        $rate->save();
        
        return redirect('/rates')->with('success', 'Rate added');
   }

    public function getrate($rate_date)
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $rate =  DB::table('rates')->where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->where('start_rate_date', '<=', $rate_date)->where('end_rate_date','>=',$rate_date)->get();            
        return response()->json($rate);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $rate = Rate::where('companyid', '=', $companyid)->find($id);        
        return view('rates.edit')->with('rate', $rate);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'start_rate_date' => 'required',
            'end_rate_date' => 'required',
            'buyprice'  => 'required|numeric',
            'sellprice' => 'required|numeric'
        ]);

        $user = Auth::user();
        $user_id = $user->id;

        $rate = Rate::find($id);
        $rate->companyid = $companyid;
        $rate->start_rate_date = $request->input('start_rate_date');
        $rate->end_rate_date = $request->input('end_rate_date');
        $rate->buyprice  = $request->input('buyprice');
        $rate->sellprice = $request->input('sellprice');
        $rate->updated_by = $user_id;
        $rate->save();
        
        return redirect('/rates')->with('success', 'Rate Updated');
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $rate = Rate::where('companyid', '=', $companyid)->find($id);
        $rate->delete();
        $response = array('response' => 'Rate deleted', 'success' => true);
        return $response;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rate;
use Validator;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rates = Rate::all();
        return response()->json($rates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(($request->all()), [
            'rate_date' => 'required',
            'fueltype'  => 'required',
            'buyprice'  => 'required',
            'sellprice' => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $rate = new Rate;
            $rate->rate_date = $request->input('rate_date');
            $rate->fueltype  = $request->input('fueltype');
            $rate->buyprice  = $request->input('buyprice');
            $rate->sellprice = $request->input('sellprice');
            $rate->save();
            
            return response()->json($rate);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($fueltype, $rate_date)
    {
        /*$rate = Rate::find($id);
        return response()->json($rate);*/
        $rate = DB::table('rates')
                     ->select(DB::raw('*'))
                     ->where('fueltype', '=', $fueltype)
                     ->where('rate_date', '=', $rate_date)
                     ->get();
        return response()->json($rate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(($request->all()), [
            'rate_date' => 'required',
            'fueltype'  => 'required',
            'buyprice'  => 'required',
            'sellprice' => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $rate = Rate::find($id);
            $rate->rate_date = $request->input('rate_date');
            $rate->fueltype  = $request->input('fueltype');
            $rate->buyprice  = $request->input('buyprice');
            $rate->sellprice = $request->input('sellprice');
            $rate->save();
            
            return response()->json($rate);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rate = Rate::find($id);
        $rate->delete();
        $response = array('response' => 'Rate deleted', 'success' => true);
        return $response;
    }
}

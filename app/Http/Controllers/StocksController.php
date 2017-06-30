<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Validator;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::all();
        return response()->json($stocks);
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
            'stock_date'    => 'required',
            'fueltype'      => 'required',
            'openvol'       => 'required',
            'closevol'      => 'required',
            'purchasedvol'  => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $stock = new Stock;
            $stock->stock_date   = $request->input('stock_date');
            $stock->fueltype     = $request->input('fueltype');
            $stock->openvol      = $request->input('openvol');
            $stock->closevol     = $request->input('closevol');
            $stock->purchasedvol = $request->input('purchasedvol');
            $stock->save();
            
            return response()->json($stock);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = Stock::find($id);
        return response()->json($stock);
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
            'stock_date'    => 'required',
            'fueltype'      => 'required',
            'openvol'       => 'required',
            'closevol'      => 'required',
            'purchasedvol'  => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $stock = Stock::find($id);
            $stock->stock_date   = $request->input('stock_date');
            $stock->fueltype     = $request->input('fueltype');
            $stock->openvol      = $request->input('openvol');
            $stock->closevol     = $request->input('closevol');
            $stock->purchasedvol = $request->input('purchasedvol');
            $stock->save();
            
            return response()->json($stock);
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
        $stock = Stock::find($id);
        $stock->delete();
        $response = array('response' => 'Stock deleted', 'success' => true);
        return $response;
    }
}

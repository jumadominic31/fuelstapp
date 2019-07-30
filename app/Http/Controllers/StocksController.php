<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Validator;

class StocksController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stocks = Stock::all();
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
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

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $stock = Stock::find($id);
        return response()->json($stock);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
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

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $stock = Stock::find($id);
        $stock->delete();
        $response = array('response' => 'Stock deleted', 'success' => true);
        return $response;
    }
}

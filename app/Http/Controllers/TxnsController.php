<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Txn;
use Validator;

class TxnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $txns = Txn::all();
        return response()->json($txns);
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
            'userid'    => 'required',
            'stationid' => 'required',
            'vehregno'  => 'required',
            'amount'    => 'required',
            'volume'    => 'required',
            'sellprice' => 'required',
            'fueltype'  => 'required',
            'paymethod' => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $txnid          = new Txn();
            $lasttxnid      = $txnid->orderBy('id', 'desc')->pluck('id')->first();
            $newtxnid       = $lasttxnid + 1;
            $txn = new Txn;
            $txn->userid    = $request->input('userid');
            $txn->receiptno = date('y').date('m').date('d').$newtxnid;
            $txn->stationid = $request->input('stationid');
            $txn->vehregno  = $request->input('vehregno');
            $txn->amount    = $request->input('amount');
            $txn->volume    = $request->input('volume');
            $txn->sellprice = $request->input('sellprice');
            $txn->fueltype  = $request->input('fueltype');
            $txn->paymethod = $request->input('paymethod');
            $txn->save();
            
            return response()->json($txn);
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
        $txn = Txn::find($id);
        return response()->json($txn);
    }
    
    public function dailysumm($userid, $date)
    {
        /*$rate = Rate::find($id);
        return response()->json($rate);*/
        $txn = DB::table('txns')
                     ->select('userid', DB::raw('sum(amount) as total_sales'))
                     ->where('userid', '=', $userid)
                     ->where(DB::raw('date(created_at)'), '=', $date)
                     ->groupBy('userid')
                     ->get();
        return response()->json($txn);
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
            'userid'    => 'required',
            'stationid' => 'required',
            'vehregno'  => 'required',
            'amount'    => 'required',
            'volume'    => 'required',
            'sellprice' => 'required',
            'fueltype'  => 'required',
            'paymethod' => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $txn = Txn::find($id);
            $txn->userid    = $request->input('userid');
            $txn->stationid = $request->input('stationid');
            $txn->vehregno  = $request->input('vehregno');
            $txn->amount    = $request->input('amount');
            $txn->volume    = $request->input('volume');
            $txn->sellprice = $request->input('sellprice');
            $txn->fueltype  = $request->input('fueltype');
            $txn->paymethod = $request->input('paymethod');
            $txn->save();
            
            return response()->json($txn);
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
        $txn = Txn::find($id);
        $txn->delete();
        $response = array('response' => 'Txn deleted', 'success' => true);
        return $response;
    }
}

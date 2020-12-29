<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Vehicle;
use App\Vehcollection;
use App\Credit;
use App\Owner;
use Auth;

class CollectionsController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $collections = Credit::where('company_id', '=', $companyid)->orderBy('created_at','desc')->paginate(10);
        return View('collections.index')->with('collections', $collections);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $vehicles = Vehicle::where('companyid', '=', $companyid)->pluck('num_plate', 'id')->all();
        return view('collections.create', ['vehicles' => $vehicles]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userid = $user->id;
        $companyid = $user->companyid;
        $this->validate($request, [
            'veh_id' => 'required',
            'amount' => 'required',
            'paymethod' => 'required'
        ]);

        DB::transaction(function () use($companyid, $userid, $request) {
        
            $veh_id = $request->input('veh_id');
            $amount = $request->input('amount');
            $owner_id = Vehicle::where('companyid', '=', $companyid)->where('id', '=', $veh_id)->select('owner_id')->pluck('owner_id')->first();

            // update the credit collection table
            $vehcoll = new Vehcollection();
            $vehcoll->vehicle_id = $veh_id;
            $vehcoll->company_id = $companyid;
            $vehcoll->owner_id = $owner_id;
            $vehcoll->amount = $amount;
            $vehcoll->paymethod = $request->input('paymethod');
            $vehcoll->updated_by = $userid;
            $vehcoll->save();

            // check if there is an existing entry for this customer
            $prevclosing = Credit::where('company_id', '=', $companyid)->where('vehicle_id', '=', $veh_id)->select('closing')->orderBy('id', 'desc')->pluck('closing')->first();

            if (is_null($prevclosing))
            {
                //if doesnt exist credit
                $opening = 0;
                $closing = $amount;
            }
            else
            {
                // if exists get closing bal, new bal
                $opening = $prevclosing;
                $closing = $prevclosing + $amount;
            }        
            
            $credit = new Credit();
            $credit->vehicle_id = $veh_id;
            $credit->company_id = $companyid;
            $credit->owner_id = $owner_id;
            $credit->opening = $opening;
            $credit->purchase = 0;
            $credit->payment = $amount;
            $credit->closing = $closing;
            $credit->updated_by = $userid;
            $credit->save();
        });

        return redirect('/collections')->with('success', 'Collection Created');
    }

    public function getcreditownbal($id)
    {
        $companyid = Auth::user()->companyid;
        $owner_name = Vehicle::join('owners', 'vehicles.owner_id', '=', 'owners.id')->where('vehicles.companyid', '=', $companyid)->where('vehicles.id', '=', $id)->select('owners.fullname as fullname')->pluck('fullname')->first();
        $balance = Credit::where('company_id', '=', $companyid)->where('vehicle_id', '=', $id)->select('closing')->orderBy('id', 'desc')->pluck('closing')->first();
        if (is_null($balance))
        {
            $balance = 0;
        } 
        return response()->json(['owner_name' => $owner_name, 'balance' => $balance]);
    }
}

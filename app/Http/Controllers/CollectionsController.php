<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Vehicle;
use App\Vehcollection;
use App\Credit;
use App\Owner;
use Auth;
use Carbon\Carbon;

class CollectionsController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $collections = DB::table('credits as t')->where('t.company_id', '=', $companyid)->select('t.*')->leftJoin('credits as t1', function ($join) {$join->on('t.owner_id','=','t1.owner_id'); $join->on('t.id', '<', 't1.id'); })->join('owners', 'owners.id', '=', 't.owner_id')->select('owners.fullname', 't.owner_id', 't.closing')->whereNull('t1.owner_id')->paginate(10);
        // $collections = DB::table('credits as t')->where('t.company_id', '=', $companyid)->select('t.*')->leftJoin('credits as t1', function ($join) {$join->on('t.owner_id','=','t1.owner_id'); $join->on('t.id', '<', 't1.id'); })->whereNull('t1.owner_id')->paginate(10);
        return View('collections.index', ['collections' => $collections]);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $vehicles = Vehicle::where('companyid', '=', $companyid)->orderBy('num_plate')->pluck('num_plate', 'id')->all();
        $owners = Owner::where('companyid', '=', $companyid)->orderBy('own_num')->select(DB::raw("CONCAT(own_num, ' - ',fullname) AS owner"),'id')->pluck('owner', 'id')->all();
        $curr_date = Carbon::now()->format('Y-m-d');
        return view('collections.create', ['owners' => $owners, 'vehicles' => $vehicles,'curr_date' => $curr_date]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userid = $user->id;
        $companyid = $user->companyid;
        $this->validate($request, [
            'date' => 'required',
            'owner_id' => 'required',
            'amount' => 'required',
            'paymethod' => 'required'
        ]);

        DB::transaction(function () use($companyid, $userid, $request) {
        
            $veh_id = $request->input('veh_id');
            $amount = $request->input('amount');
            $owner_id = $request->input('owner_id');

            // update the credit collection table
            $vehcoll = new Vehcollection();
            $vehcoll->coll_date = $request->input('date');
            $vehcoll->vehicle_id = $veh_id;
            $vehcoll->company_id = $companyid;
            $vehcoll->owner_id = $owner_id;
            $vehcoll->amount = $amount;
            $vehcoll->paymethod = $request->input('paymethod');
            $vehcoll->updated_by = $userid;
            $vehcoll->save();

            // check if there is an existing entry for this customer
            $prevclosing = Credit::where('company_id', '=', $companyid)->where('owner_id', '=', $owner_id)->select('closing')->orderBy('id', 'desc')->pluck('closing')->first();

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
        $vehicles = Vehicle::where('companyid', '=', $companyid)->where('owner_id', '=', $id)->select('num_plate', 'id')->get();
        $balance = Credit::where('company_id', '=', $companyid)->where('owner_id', '=', $id)->select('closing')->orderBy('id', 'desc')->pluck('closing')->first();
        if (is_null($balance))
        {
            $balance = 0;
        } 
        return response()->json(['vehicles' => $vehicles, 'balance' => $balance]);
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $owner_name = Owner::where('companyid', '=', $companyid)->where('id', '=', $id)->select('fullname')->pluck('fullname')->first();
        $collections = Credit::where('company_id', '=', $companyid)->where('owner_id', '=', $id)->orderBy('created_at','desc')->paginate(10);
        return View('collections.show', [ 'owner_name' => $owner_name,'collections' => $collections]);
    }
}

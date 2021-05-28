<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Txn;
use App\Vehicle;
use App\Owner;
use Validator;
use App\Eoday;
use App\Station;
use App\Pump;
use App\Company;
use App\User;
use App\Credit;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Auth;
use Excel;
use PDF;
use App\Classes\AfricasTalkingGateway;
use App\Classes\AfricasTalkingGatewayException;

class TxnsController extends Controller
{

    public function index(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $company_details = Company::where('id', '=', $companyid)->get();
        $stations = Station::where('companyid', '=', $companyid)->orderBy('station')->pluck('station', 'id')->all();
        $attendants = User::where('companyid', '=', $companyid)->orderBy('fullname')->pluck('fullname', 'id')->all();
        $curr_date = date('Y-m-d');
        $last_10_date = date('Y-m-d', strtotime('-1 days'));
        $stationid = Auth::user()->stationid;
        $vehregno = $request->input('vehregno');
        $receiptno = $request->input('receiptno');
        $first_date = $request->input('first_date');
        $last_date = $request->input('last_date');
        $fueltype = $request->input('fueltype');
        $paymethod = $request->input('paymethod');
        $stationsel = $request->input('stationsel');
        $attendantid = $request->input('attendantid');
        
        $txns = Txn::where('companyid', '=', $companyid);
        $tot_coll = Txn::select('paymethod', DB::raw('sum(amount) as tot_amount'))->where('companyid', '=', $companyid);
        if (Auth::user()->usertype == 'stationadmin'){
            $txns = $txns->where('stationid' , '=' , $stationid);
            $tot_coll = $tot_coll->where('stationid' , '=' , $stationid);
        }
        if ($vehregno != NULL){
            $txns = $txns->where('vehregno','like','%'.$vehregno.'%');
            $tot_coll = $tot_coll->where('vehregno','like','%'.$vehregno.'%');
        }
        if ($receiptno != NULL){
            $txns = $txns->where('receiptno','like','%'.$receiptno.'%');
            $tot_coll = $tot_coll->where('receiptno','like','%'.$receiptno.'%');
        }
        if ($fueltype != NULL){
            $txns = $txns->where('fueltype','=', $fueltype);
            $tot_coll = $tot_coll->where('fueltype','=', $fueltype);
        }
        if ($paymethod != NULL){
            $txns = $txns->where('paymethod','=', $paymethod);
            $tot_coll = $tot_coll->where('paymethod','=', $paymethod);
        }
        if ($attendantid != NULL){
            $txns = $txns->where('userid','=', $attendantid);
            $tot_coll = $tot_coll->where('userid','=', $attendantid);
        }
        if ($stationsel != NULL){
            $txns = $txns->where('stationid','=', $stationsel);
            $tot_coll = $tot_coll->where('stationid','=', $stationsel);
        }
        if ($first_date != NULL){
            if ($last_date != NULL){
                $txns = $txns->where(DB::raw('date(txns.created_at)'), '<=', $last_date)->where(DB::raw('date(txns.created_at)'),'>=',$first_date);
                $tot_coll = $tot_coll->where(DB::raw('date(txns.created_at)'), '<=', $last_date)->where(DB::raw('date(txns.created_at)'),'>=',$first_date);
            } 
            else{
                $txns = $txns->where(DB::raw('date(txns.created_at)'), '=', $first_date);
                $tot_coll = $tot_coll->where(DB::raw('date(txns.created_at)'), '=', $first_date);
            }
        }
        else{
            $txns = $txns->where(DB::raw('date(txns.created_at)'),'>=',$curr_date);
            $tot_coll = $tot_coll->where(DB::raw('date(txns.created_at)'),'>=',$curr_date);
        }
        $txns = $txns->orderBy('created_at','desc')->limit(300)->paginate(30);
        // $txns = $txns->orderBy('created_at','desc')->get();

        // $totals = $tot_coll->groupBy('paymethod')->get();
        $tots2 = $tot_coll->groupBy('paymethod')->get();
        $totals = ['cash' => 0, 'mpesa' => 0, 'credit' => 0, 'visa' => 0, 'tot_coll' => 0];

        foreach ($tots2 as $tots){
            if ($tots['paymethod'] == 'Cash'){
                $totals['cash'] = $tots['tot_amount'];
            }
            if ($tots['paymethod'] == 'MPesa'){
                $totals['mpesa'] = $tots['tot_amount'];
            }
            if ($tots['paymethod'] == 'Credit'){
                $totals['credit'] = $tots['tot_amount'];
            }
            if ($tots['paymethod'] == 'Visa'){
                $totals['visa'] = $tots['tot_amount'];
            }
        }
        $totals['tot_coll'] = $totals['cash'] + $totals['mpesa'] + $totals['credit'] + $totals['visa'];
        // $totals = implode(', ', $totals);
        $tot_coll = $totals['tot_coll'];
        
        // $totals['tot_coll'] = $tot_coll->pluck('tot_amount')->first();
        
        if ($request->submitBtn == 'CreatePDF') {
            $pdf = PDF::loadView('pdf.txns', ['txns' => $txns, 'tot_coll' => $tot_coll, 'company_details' => $company_details, 'curr_date' => $curr_date]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('txns.pdf');
        }

        return View('txns.index', ['txns' => $txns, 'vehregno' => $vehregno, 'receiptno' => $receiptno, 'attendants' => $attendants, 'stations' => $stations, 'totals' => $totals]);
    }

    public function edit($id)
    {
        $txn = Txn::find($id);
        $stationid = $txn->stationid;
        $pumps = Pump::where('stationid', '=', $stationid)->pluck('pumpname','id');
        return view('txns.edit', ['txn' => $txn, 'pumps' => $pumps]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vehregno'  => 'required',
            'amount'    => 'required|numeric',
            'sellprice' => 'required|numeric',
            'fueltype'  => 'required',
            'paymethod' => 'required',
            'pumpid'    => 'required'
        ]);
        
        
        $txn = Txn::find($id);
        $txn->vehregno  = $request->input('vehregno');
        $txn->amount    = $request->input('amount');
        $txn->sellprice = $request->input('sellprice');
        $txn->volume    = $txn->amount / $txn->sellprice;
        $txn->fueltype  = $request->input('fueltype');
        $txn->paymethod = $request->input('paymethod');
        $txn->pumpid    = $request->input('pumpid');
        $txn->save();
        
        return redirect('/txns')->with('success', 'Transaction details updated');
    }

    public function cancel(Request $request, $id)
    {
        $txn = Txn::find($id);
        $txn->amount = 0;
        $txn->cancelled  = '1';
        $txn->save();

        return redirect('/txns')->with('success', 'Transaction id '.$id.' cancelled');
    }

    public function loyaltySummary(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $curr_month = date('Y').'-'.date('m');
        $company_details = Company::where('id', '=', $companyid)->get();
        $curr_date = date('Y-m-d');
        //$stationid = Auth::user()->stationid;
        
        $vehicles = Vehicle::where('companyid', '=', $companyid)->select('num_plate')->get()->toArray();
        $txns = Txn::select('vehregno', DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->whereNotIn('vehregno', $vehicles)->groupBy('vehregno');

        if ($request->submitBtn == 'Submit'){
            $this->validate($request, [
                'month' => 'required'
            ]);
        }
        
        $vehregno = $request->input('vehregno');
        $month = $request->input('month');
        session(['fuelstapp.loyaltymonth' => $month]);
        if ($month != NULL){
            $txns = $txns->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month);
        }
        else {
            $txns = $txns->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $curr_month);
        }
        if ($vehregno != NULL){
            $txns = $txns->where('vehregno','like','%'.$vehregno.'%');
        }
        
        if ($request->submitBtn == 'CreatePDF') {
            $txns = $txns->limit(150)->get(); //beyond 150 it gives 500 error in prod
            $pdf = PDF::loadView('pdf.loyalty', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'month' => $month]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('loyalty.pdf');
        } 

        session(['fuelstapp.loyaltymonth' => $curr_month]);
        $txns_count = $txns->get()->count();
        $txns = $txns->paginate(30);
        return View('loyalty.index', ['txns' => $txns, 'txns_count' => $txns_count]);
    }

    public function memloyaltySummary(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $curr_month = date('Y').'-'.date('m');
        $company_details = Company::where('id', '=', $companyid)->get();
        $curr_date = date('Y-m-d');

        $owners = Owner::where('companyid', '=', $companyid)->pluck('fullname','id')->toArray();
        // $txns = Txn::select('ownerid', DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where('ownerid', '!=', '0')->groupBy('ownerid');
        $vehicles = Vehicle::where('companyid', '=', $companyid)->select('num_plate')->get()->toArray();
        $txns = Txn::select('ownerid', DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->whereIn('vehregno', $vehicles)->groupBy('ownerid');

        if ($request->submitBtn == 'Submit'){
            $this->validate($request, [
                'month' => 'required'
            ]);
        }
        
        $owner_id = $request->input('owner_id');
        $month = $request->input('month');
        session(['fuelstapp.loyaltymonth' => $month]);
        if ($month != NULL){
            $txns = $txns->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month);
        }
        else {
            $txns = $txns->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $curr_month);
        }
        if ($owner_id != NULL){
            $txns = $txns->where('ownerid','=', $owner_id);
        }
        
        if ($request->submitBtn == 'CreatePDF') {
            $txns = $txns->limit(150)->get(); //beyond 150 it gives 500 error in prod
            $pdf = PDF::loadView('pdf.loyalty', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'month' => $month]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('loyalty.pdf');
        } 

        session(['fuelstapp.loyaltymonth' => $curr_month]);
        $txns_count = $txns->get()->count();
        $txns = $txns->paginate(30);
        return View('loyalty.members', ['txns' => $txns, 'txns_count' => $txns_count, 'owners' => $owners]);
    }

    public function loyaltyDetails($vehregno)
    {
        $companyid = Auth::user()->companyid;
        // $company_details = Company::where('id', '=', $companyid)->get();
        // $curr_date = date('Y-m-d');
        $month = session('fuelstapp.loyaltymonth');
        
        $txns = Txn::where('companyid', '=', $companyid)->where('vehregno','=',$vehregno)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month)->orderBy('created_at','desc');

        // if ($request->submitBtn == 'CreatePDF') {
        //     $txns = $txns->limit(150)->get(); //beyond 150 it gives 500 error in prod
        //     $pdf = PDF::loadView('pdf.nonmemtxns', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'month' => $month]);
        //     $pdf->setPaper('A4', 'landscape');
        //     return $pdf->stream('loyalty.pdf');
        // }

        $txns = $txns->paginate(30);

        return view('loyalty.show',['txns'=> $txns]);
    }

    public function memloyaltyDetails($ownerid)
    {
        $companyid = Auth::user()->companyid;
        // $company_details = Company::where('id', '=', $companyid)->get();
        // $curr_date = date('Y-m-d');
        $month = session('fuelstapp.loyaltymonth');
        
        $txns = Txn::where('companyid', '=', $companyid)->where('ownerid','=',$ownerid)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month)->orderBy('created_at','desc');

        // if ($request->submitBtn == 'CreatePDF') {
        //     $txns = $txns->limit(150)->get(); //beyond 150 it gives 500 error in prod
        //     $pdf = PDF::loadView('pdf.nonmemtxns', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'month' => $month]);
        //     $pdf->setPaper('A4', 'landscape');
        //     return $pdf->stream('loyalty.pdf');
        // }

        $txns = $txns->paginate(30);

        return view('loyalty.memshow',['txns'=> $txns]);
    }

    public function gettxns()
    {
        $companyid = Auth::user()->companyid;
        $txns = Txn::where('companyid', '=', $companyid)->get();
        return response()->json(['status' => 'success' , 'txns' => $txns]);
    }

    public function posttxn(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $user = JWTAuth::parseToken()->toUser();
        $validator = Validator::make(($request->all()), [
            //'userid'    => 'required',
            'stationid' => 'required',
            'vehregno'  => 'required',
            'amount'    => 'required',
            'volume'    => 'required',
            'sellprice' => 'required',
            'fueltype'  => 'required',
            'paymethod' => 'required',
            'pumpid'    => 'required',
        ]);

        //$stationid = $user->stationid;
        $stationid = $request->input('stationid');
        $station = Station::select('station')->where('id', '=', $stationid)->pluck('station')->first();
        $vehregno = $request->input('vehregno');
        $vehregno = strtoupper($vehregno);
        $vehregno = str_replace( ' ', '', $vehregno);
        $paymethod = $request->input('paymethod');
        $amount = $request->input('amount');
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $veh_id = Vehicle::where('companyid', '=', $companyid)->where('num_plate', '=', $vehregno)->select('id')->pluck('id')->first();
            $owner_id = Vehicle::join('owners', 'vehicles.owner_id', '=', 'owners.id')->select('owners.id')->where('vehicles.companyid', '=', $companyid)->where('vehicles.num_plate', '=', $vehregno)->pluck('owners.id')->first();
            $owner_phone = Vehicle::join('owners', 'vehicles.owner_id', '=', 'owners.id')->select('owners.phone')->where('vehicles.companyid', '=', $companyid)->where('vehicles.num_plate', '=', $vehregno)->pluck('owners.phone')->first();
            if ($owner_id == NULL){
                $owner_id = 0;
            }
            $txn = collect();

            DB::transaction(function () use($companyid, $user, $request, $vehregno, $stationid, $amount, $paymethod, $owner_id, $veh_id, &$txn) {
               
                $txnid          = new Txn();
                $lasttxnid      = $txnid->orderBy('id', 'desc')->pluck('id')->first();
                $newtxnid       = $lasttxnid + 1;
                $txn = new Txn;
                //$txn->userid    = $request->input('userid');
                $txn->userid    = $user->id;
                $txn->receiptno = date('y').date('m').date('d').$newtxnid;
                $txn->stationid = $stationid;
                // $txn->stationid = $user->stationid;
                $txn->companyid = $companyid;
                $txn->vehregno  = $vehregno;
                $txn->ownerid   = $owner_id;
                $txn->amount    = $amount;
                $txn->volume    = $request->input('volume');
                $txn->sellprice = $request->input('sellprice');
                $txn->fueltype  = $request->input('fueltype');
                $txn->paymethod = $paymethod;
                $txn->pumpid    = $request->input('pumpid');
                $txn->save();

                //add transaction to credit table if paymethod is credit
                if ($paymethod == 'Credit')
                {
                    // check if there is an existing entry for this customer
                    $prevclosing = Credit::where('company_id', '=', $companyid)->where('vehicle_id', '=', $veh_id)->select('closing')->orderBy('id', 'desc')->pluck('closing')->first();

                    if (is_null($prevclosing))
                    {
                        //if doesnt exist credit
                        $opening = 0;
                        $closing = 0 - $amount;
                    }
                    else
                    {
                        // if exists get closing bal, new bal
                        $opening = $prevclosing;
                        $closing = $prevclosing - $amount;
                    }        
                    
                    $credit = new Credit();
                    $credit->vehicle_id = $veh_id;
                    $credit->company_id = $companyid;
                    $credit->owner_id = $owner_id;
                    $credit->opening = $opening;
                    $credit->purchase = 0 - $amount;
                    $credit->payment = 0;
                    $credit->closing = $closing;
                    $credit->updated_by = $user->id;
                    $credit->save();
                }

            });

            if ($companyid == '3')
            {
                // $owner_phone = Vehicle::join('owners', 'vehicles.owner_id', '=', 'owners.id')->select('owners.phone')->where('vehicles.companyid', '=', $companyid)->where('vehicles.num_plate', '=', $txn->vehregno)->pluck('owners.phone')->first();

                // Send transaction SMS
                if ($owner_phone != NULL)
                {
                    $atgusername   = env('ATGUSERNAME');
                    $atgapikey     = env('ATGAPIKEY');
                    $senderid   = env('ATGSENDERID');
                    $recipients = '+'.$owner_phone;
                    $message    = "Txn details\nStation: ".$station."\nReceipt#: ".$txn->receiptno."\nVehicle: ".$txn->vehregno."\nAmount: ".$txn->amount."\n Vol: ".$txn->volume;
                    $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                    try 
                    { 
                      $send_results = $gateway->sendMessage($recipients, $message, $senderid);
                    }
                    catch ( AfricasTalkingGatewayException $e )
                    {
                      echo 'Encountered an error while sending: '.$e->getMessage();
                    }
                }
            }
            return response()->json(['txn' => $txn, 'station' => $station, 'status' => 'success'], 201);
        }
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $txn = Txn::find($id);
        if (!$txn) {
            return response()->json(['message' => 'Txn not found', 'status' => 'failure'], 404);
        }
        return response()->json(['txn' => $txn, 'status' => 'success'], 200);
    }
    
    public function dailysumm($userid, $date)
    {
        $companyid = Auth::user()->companyid;
        /*$rate = Rate::find($id);
        return response()->json($rate);*/
        $txn = DB::table('txns')
                     ->select('userid','fueltype','paymethod', DB::raw('sum(volume) as total_vol'), DB::raw('sum(amount) as total_sales'))
                     ->where('userid', '=', $userid)
                     ->where('companyid', '=', $companyid)
                     ->where(DB::raw('date(created_at)'), '=', $date)
                     ->groupBy('userid')
                     ->groupBy('fueltype')
                     ->groupBy('paymethod')
                     ->groupBy('volume')
                     ->get();
        return response()->json($txn);
    }

    public function salessumm(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $curr_date = date('Y-m-d');
        $stationid = Auth::user()->stationid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->toArray();

        $summ_date_1 = $request->input('summ_date_1');
        $summ_date_2 = $request->input('summ_date_2');
        $station = $request->input('station');
        $fueltype = $request->input('fueltype');

        $txns = Txn::select('userid', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->groupBy('userid')->orderBy('total_sales', 'desc');

        if ($station != NULL){
            $txns = $txns->where('stationid','=', $station);
        }
        if ($fueltype != NULL){
            $txns = $txns->where('fueltype','=', $fueltype);
        }
        if ($summ_date_1 != NULL){
            if ($summ_date_2 != NULL){
                $txns = $txns->where(DB::raw('date(txns.created_at)'), '<=', $summ_date_2)->where(DB::raw('date(txns.created_at)'),'>=',$summ_date_1);
            } 
            else{
                $txns = $txns->where(DB::raw('date(txns.created_at)'), '=', $summ_date_1);
            }
        }
        else {
            $txns = $txns->where(DB::raw('date(txns.created_at)'), '=', $curr_date);
        }

        if (Auth::user()->usertype == 'stationadmin'){ 
            $txns = $txns->where('stationid', '=', $stationid);
        }

        $txns = $txns->get();

        return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);
    }

    //monthly summary for all vehicles
    public function monthsummary()
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        $curr_date = date('Y-m-d');

        $txns = Txn::get();

        return view('txns.monthsummary', ['txns' => $txns]);
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $txn = Txn::find($id);
        $txn->delete();
        $response = array('response' => 'Txn deleted', 'success' => true);
        return $response;
    }

    public function downloadExcel($type)
    {
        $companyid = Auth::user()->companyid;
    	$data = Txn::where('companyid', '=', $companyid)->get()->toArray();
		return Excel::create('txns_details', function($excel) use ($data) {
			$excel->sheet('txns', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

    public function downloadloyaltyExcel($type)
    {
        $companyid = Auth::user()->companyid;
    	$data = Txn::select('vehregno', DB::raw('DATE_FORMAT(created_at, "%Y-%m") as Month'), DB::raw('sum(volume) as total_vol') )->where('companyid', '=', $companyid)->groupBy('vehregno' )->groupBy( DB::raw(' DATE_FORMAT( created_at, "%Y-%m")'))->get()->toArray();
		return Excel::create('loyalty_details', function($excel) use ($data) {
			$excel->sheet('loyalty', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
}

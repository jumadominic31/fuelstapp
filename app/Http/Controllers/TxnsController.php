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
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Auth;
use Excel;
use PDF;

class TxnsController extends Controller
{

    public function index(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $company_details = Company::where('id', '=', $companyid)->get();
        $stations = Station::where('companyid', '=', $companyid)->pluck('station', 'id')->all();
        $attendants = User::where('usertype','=','attendant')->where('companyid', '=', $companyid)->pluck('fullname', 'id')->all();
        $curr_date = date('Y-m-d');
        $stationid = Auth::user()->stationid;
        $vehregno = $request->input('vehregno');
        $receiptno = $request->input('receiptno');
        $first_date = $request->input('first_date');
        $last_date = $request->input('last_date');
        $fueltype = $request->input('fueltype');
        $paymethod = $request->input('paymethod');
        $stationsel = $request->input('stationsel');
        $attendantid = $request->input('attendantid');
        if (Auth::user()->usertype == 'stationadmin'){
            if ($request->isMethod('POST')){
                $txns = Txn::where('companyid', '=', $companyid)->where('stationid' , '=' , $stationid);
                $tot_coll = Txn::select('companyid', DB::raw('sum(amount) as tot_amount'))->where('companyid', '=', $companyid)->where('stationid' , '=' , $stationid);
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
                /*if ($stationsel != NULL){
                    $txns = $txns->where('stationid','=', $stationsel);
                    $tot_coll = $tot_coll->where('stationid','=', $stationsel);
                }*/
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
                $txns = $txns->orderBy('created_at','desc')->limit(50)->get();
                $tot_coll = $tot_coll->groupBy('companyid')->pluck('tot_amount')->first();
                if ($request->submitBtn == 'CreatePDF') {
                    $pdf = PDF::loadView('pdf.txns', ['txns' => $txns, 'tot_coll' => $tot_coll, 'company_details' => $company_details, 'curr_date' => $curr_date]);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream('txns.pdf');
                } 
                return View('txns.index', ['txns' => $txns, 'vehregno' => $vehregno, 'receiptno' => $receiptno, 'attendants' => $attendants, 'stations' => $stations ]);
            }
            $txns = Txn::where('companyid', '=', $companyid)->where('stationid' , '=' , $stationid)->orderBy('created_at','desc')->limit(50)->get();

            return View('txns.index', ['txns' => $txns, 'vehregno' => $vehregno, 'receiptno' => $receiptno, 'attendants' => $attendants, 'stations' => $stations ]);
        }

        if ($request->isMethod('POST')){
            $txns = Txn::where('companyid', '=', $companyid);
            $tot_coll = Txn::select('companyid', DB::raw('sum(amount) as tot_amount'))->where('companyid', '=', $companyid);
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
            $txns = $txns->orderBy('created_at','desc')->limit(50)->get();
            $tot_coll = $tot_coll->groupBy('companyid')->pluck('tot_amount')->first();
            if ($request->submitBtn == 'CreatePDF') {
                $pdf = PDF::loadView('pdf.txns', ['txns' => $txns, 'tot_coll' => $tot_coll, 'company_details' => $company_details, 'curr_date' => $curr_date]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('txns.pdf');
            }
            return View('txns.index', ['txns' => $txns, 'vehregno' => $vehregno, 'receiptno' => $receiptno, 'attendants' => $attendants, 'stations' => $stations ]);
        }
        $txns = Txn::where('companyid', '=', $companyid)->orderBy('created_at','desc')->limit(50)->get();
        return View('txns.index', ['txns' => $txns, 'vehregno' => $vehregno, 'receiptno' => $receiptno, 'attendants' => $attendants, 'stations' => $stations]);
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

    public function loyaltySummary(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $curr_month = date('Y').'-'.date('m');
        $company_details = Company::where('id', '=', $companyid)->get();
        $curr_date = date('Y-m-d');
        //$stationid = Auth::user()->stationid;
        
        if ($request->isMethod('POST')){
            $this->validate($request, [
                'month' => 'required'
            ]);
            $vehregno = $request->input('vehregno');
            $month = $request->input('month');
            session(['fuelstapp.loyaltymonth' => $month]);
            $txns = Txn::select('vehregno', DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month);
            if ($vehregno != NULL){
                $txns = $txns->where('vehregno','like','%'.$vehregno.'%');
            }
            $txns = $txns->groupBy('vehregno')->limit(50)->get();
            
            if ($request->submitBtn == 'CreatePDF') {
                $pdf = PDF::loadView('pdf.loyalty', ['txns' => $txns, 'company_details' => $company_details, 'curr_date' => $curr_date, 'month' => $month]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('loyalty.pdf');
            } 
            return View('loyalty.index', ['txns' => $txns ]);
        }
        session(['fuelstapp.loyaltymonth' => $curr_month]);
        $txns = Txn::select('vehregno', DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $curr_month)->groupBy('vehregno')->paginate(30);
        return View('loyalty.index', ['txns' => $txns]);
    }

    public function loyaltyDetails($vehregno)
    {
        $companyid = Auth::user()->companyid;
        $month = session('fuelstapp.loyaltymonth');
        
        $txns = Txn::where('companyid', '=', $companyid)->where('vehregno','=',$vehregno)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month)->orderBy('created_at','desc')->paginate(30);

        return view('loyalty.show',['txns'=> $txns]);
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
            //'stationid' => 'required',
            'vehregno'  => 'required',
            'amount'    => 'required',
            'volume'    => 'required',
            'sellprice' => 'required',
            'fueltype'  => 'required',
            'paymethod' => 'required',
            'pumpid'    => 'required',
        ]);
        
        if ($validator->fails()){
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
            $txnid          = new Txn();
            $lasttxnid      = $txnid->orderBy('id', 'desc')->pluck('id')->first();
            $newtxnid       = $lasttxnid + 1;
            $txn = new Txn;
            //$txn->userid    = $request->input('userid');
            $txn->userid    = $user->id;
            $txn->receiptno = date('y').date('m').date('d').$newtxnid;
            //$txn->stationid = $request->input('stationid');
            $txn->stationid = $user->stationid;
            $txn->companyid = $companyid;
            $txn->vehregno  = $request->input('vehregno');
            $txn->amount    = $request->input('amount');
            $txn->volume    = $request->input('volume');
            $txn->sellprice = $request->input('sellprice');
            $txn->fueltype  = $request->input('fueltype');
            $txn->paymethod = $request->input('paymethod');
            $txn->pumpid    = $request->input('pumpid');
            $txn->save();

            $owner_phone = Vehicle::join('owners', 'vehicles.owner_id', '=', 'owners.id')->select('owners.phone')->where('vehicles.companyid', '=', $companyid)->where('vehicles.num_plate', '=', $txn->vehregno)->pluck('owners.phone')->first();

            // Send transaction SMS
            if ($owner_phone != NULL)
            {
                $atgusername   = env('ATGUSERNAME');
                $atgapikey     = env('ATGAPIKEY');
                $senderid   = env('ATGSENDERID');
                $recipients = '+'.$owner_phone;
                $message    = "Txn details\nVehicle: ".$txn->vehregno."\nAmount: ".$txn->amount;
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
            
            return response()->json(['txn' => $txn], 201);
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
        //$summ_date = date('y').'-'.date('m').'-'.date('d');
        $curr_datetime = date('Y-m-d H:i:s');
        $stationid = Auth::user()->stationid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id');
        $lasteod_datetime = Eoday::orderBy('id', 'desc')->where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->pluck('created_at')->first();

        if ($request->isMethod('POST')){
            
            $this->validate($request, [
                'summ_date' => 'required'
            ]);

            $summ_date = $request->input('summ_date');
            $station = $request->input('station');

            if (Auth::user()->usertype == 'stationadmin'){ 
                $txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where('stationid','=',$stationid)->where(DB::raw('date(created_at)'), '=', $summ_date)->groupBy('userid')->groupBy('fueltype')->groupBy('paymethod')->get();
            
                return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);
            }

            if ($station == NULL) {
                $txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where(DB::raw('date(created_at)'), '=', $summ_date)->groupBy('userid')->groupBy('fueltype')->groupBy('paymethod')->get();
                return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);
            }

            $txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where('stationid','=',$station)->where(DB::raw('date(created_at)'), '=', $summ_date)->groupBy('userid')->groupBy('fueltype')->groupBy('paymethod')->get();
            return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);

        }

        if (Auth::user()->usertype == 'stationadmin'){ 
            //$txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('stationid','=',$stationid)->groupBy('userid')->groupBy('fueltype')->groupBy('paymethod')->get();
            $txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->where('stationid', '=' , $stationid)->where(DB::raw('date(created_at)'), '>=', $lasteod_datetime)->where(DB::raw('date(created_at)'), '<=' , $curr_datetime)->groupBy( 'userid')->groupBy( 'fueltype')->groupBy( 'paymethod')->get();
            return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);
        }

        $txns = Txn::select('userid', 'fueltype', 'paymethod', DB::raw('sum(amount) as total_sales'), DB::raw('sum(volume) as total_vol'))->where('companyid', '=', $companyid)->groupBy('userid')->groupBy('fueltype')->groupBy('paymethod')->get();
        return view('txns.salessumm',['txns'=> $txns, 'stations' => $stations]);

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Reading;
use App\Rate;
use App\Pump;
use App\Txn;
use Auth;
use App\Eoday;
use App\Othertxn;
use Validator;

class ReadingsController extends Controller
{
    public function showprev($id){
        $companyid = Auth::user()->companyid;
        $reading = new Reading;
        $prev = $reading->orderBy('id', 'desc')->where('companyid', '=', $companyid)->where('pumpid', '=', $id)->pluck('current')->first();
        return $prev;
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $numpumps = Pump::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->count();
        $curr_readings = Reading::orderBy('id', 'desc')->where('companyid', '=', $companyid)->where('stationid','=',$stationid)->skip(0)->take($numpumps)->get();
        return view('readings.create')->with(['curr_readings'=> $curr_readings]);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $eod_date = date('y').'-'.date('m').'-'.date('d');

        $day_rate = new Rate;
        
        $diesel_rate = $day_rate->where('companyid', '=', $companyid)->where('start_rate_date', '<=', $eod_date )->where('end_rate_date','>=',$eod_date )->where('fueltype','=','diesel' )->pluck('sellprice' )->first();
        $petrol_rate = $day_rate->where('companyid', '=', $companyid)->where('start_rate_date', '<=', $eod_date )->where('end_rate_date','>=',$eod_date )->where('fueltype','=','petrol' )->pluck('sellprice' )->first();
        
        if (($diesel_rate == NULL) || ($petrol_rate == NULL) ){
            return redirect('/eodays')->with('error', 'Please ask the administrator to add rates first');
        }

        $this->validate($request, [
			//current readings validation required
            'trans_details_1' => 'required',
            'diesel_1' => 'required',
            'petrol_1' => 'required',
            'trans_details_3' => 'required',
            'diesel_3' => 'required',
            'petrol_3' => 'required',
            'trans_details_4' => 'required',
            'diesel_4' => 'required',
            'petrol_4' => 'required',
            'diesel_5' => 'required',
            'petrol_5' => 'required',
            'diesel_purchases' => 'required',
            'petrol_purchases' => 'required',
            'diesel_dip' => 'required',
            'petrol_dip' => 'required'
        ]);

        $eodayid          = new Eoday();
        $lasteodayid      = $eodayid->orderBy('id', 'desc')->pluck('id')->first();
        $dieseleodayid    = $lasteodayid + 1;
        $petroleodayid    = $lasteodayid + 2;

        $userid = Auth::user()->id;
        $stationid = Auth::user()->stationid;
		$tot_diesel_vol = 0;
		$tot_petrol_vol = 0;
		
		$pumps = Pump::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->pluck('pumpname', 'id')->toArray();
		
		foreach ($pumps as $id => $pump) {
			$this->validate($request, [
				'current_'.$id => 'required'
			]);
		
			$fueltype = Pump::where('companyid', '=', $companyid)->where('id','=',$id)->pluck('fueltype')->first();
			
			${'reading_'.$pump} = new Reading;
			${'reading_'.$pump}->pumpid    = $id;
            ${'reading_'.$pump}->companyid = $companyid;
            ${'reading_'.$pump}->stationid = $stationid;
			${'reading_'.$pump}->previous  = ${'reading_'.$pump}->orderBy('id', 'desc')->where('pumpid', '=', $id)->pluck('current')->first(); //get previous reading
			${'reading_'.$pump}->current   = $request->input('current_'.$id);
			${'reading_'.$pump}->diff      = ${'reading_'.$pump}->current - ${'reading_'.$pump}->previous;
			${'reading_'.$pump}->updated_by= $userid; //get userid
			if ($fueltype == 'Diesel'){
				${'reading_'.$pump}->eoday_id  = $dieseleodayid;
				$tot_diesel_vol += ${'reading_'.$pump}->diff;
			} 
			else if ($fueltype == 'Petrol'){
				${'reading_'.$pump}->eoday_id  = $petroleodayid;
				$tot_petrol_vol += ${'reading_'.$pump}->diff;
			} 
			${'reading_'.$pump}->save();
		
		}
        
        $tot_diesel_val = $tot_diesel_vol * $diesel_rate;
        $tot_petrol_val = $tot_petrol_vol * $petrol_rate;
        $tot_val = $tot_diesel_val + $tot_petrol_val;

        $othertxn_1a = new Othertxn;
        $othertxn_1a->stationid = $stationid;
        $othertxn_1a->companyid = $companyid;
        $othertxn_1a->eoday_id  = $dieseleodayid;
        $othertxn_1a->fueltype  = 'Diesel';
        $othertxn_1a->txntype   = 'Banked';
        $othertxn_1a->txndetails= $request->input('trans_details_1');
        $othertxn_1a->amount    = $request->input('diesel_1');
        $othertxn_1a->updated_by= $userid; //get userid
        $othertxn_1a->save();

        $othertxn_1b = new Othertxn;
        $othertxn_1b->stationid = $stationid;
        $othertxn_1b->companyid = $companyid;
        $othertxn_1b->eoday_id  = $petroleodayid;
        $othertxn_1b->fueltype  = 'Petrol';
        $othertxn_1b->txntype   = 'Banked';
        $othertxn_1b->txndetails= $request->input('trans_details_1');
        $othertxn_1b->amount    = $request->input('petrol_1');
        $othertxn_1b->updated_by= $userid; //get userid
        $othertxn_1b->save();

        $othertxn_3a = new Othertxn;
        $othertxn_3a->stationid = $stationid;
        $othertxn_3a->companyid = $companyid;
        $othertxn_3a->eoday_id  = $dieseleodayid;
        $othertxn_3a->fueltype  = 'Diesel';
        $othertxn_3a->txntype   = 'MPesa';
        $othertxn_3a->txndetails= $request->input('trans_details_3');
        $othertxn_3a->amount    = $request->input('diesel_3');
        $othertxn_3a->updated_by= $userid; //get userid
        $othertxn_3a->save();

        $othertxn_3b = new Othertxn;
        $othertxn_3b->stationid = $stationid;
        $othertxn_3b->companyid = $companyid;
        $othertxn_3b->eoday_id  = $petroleodayid;
        $othertxn_3b->fueltype  = 'Petrol';
        $othertxn_3b->txntype   = 'MPesa';
        $othertxn_3b->txndetails= $request->input('trans_details_3');
        $othertxn_3b->amount    = $request->input('petrol_3');
        $othertxn_3b->updated_by= $userid; //get userid
        $othertxn_3b->save();

        $othertxn_4a = new Othertxn;
        $othertxn_4a->stationid = $stationid;
        $othertxn_4a->companyid = $companyid;
        $othertxn_4a->eoday_id  = $dieseleodayid;
        $othertxn_4a->fueltype  = 'Diesel';
        $othertxn_4a->txntype   = 'Credit';
        $othertxn_4a->txndetails= $request->input('trans_details_4');
        $othertxn_4a->amount    = $request->input('diesel_4');
        $othertxn_4a->updated_by= $userid; //get userid
        $othertxn_4a->save();

        $othertxn_4b = new Othertxn;
        $othertxn_4b->stationid = $stationid;
        $othertxn_4b->companyid = $companyid;
        $othertxn_4b->eoday_id  = $petroleodayid;
        $othertxn_4b->fueltype  = 'Petrol';
        $othertxn_4b->txntype   = 'Credit';
        $othertxn_4b->txndetails= $request->input('trans_details_4');
        $othertxn_4b->amount    = $request->input('petrol_4');
        $othertxn_4b->updated_by= $userid; //get userid
        $othertxn_4b->save();

        $othertxn_5a = new Othertxn;
        $othertxn_5a->stationid = $stationid;
        $othertxn_5a->companyid = $companyid;
        $othertxn_5a->eoday_id  = $dieseleodayid;
        $othertxn_5a->fueltype  = 'Diesel';
        $othertxn_5a->txntype   = 'Expenses';
        $othertxn_5a->txndetails= $request->input('trans_details_5');
        $othertxn_5a->amount    = $request->input('diesel_5');
        $othertxn_5a->updated_by= $userid; //get userid
        $othertxn_5a->save();

        $othertxn_5b = new Othertxn;
        $othertxn_5b->stationid = $stationid;
        $othertxn_5b->companyid = $companyid;
        $othertxn_5b->eoday_id  = $petroleodayid;
        $othertxn_5b->fueltype  = 'Petrol';
        $othertxn_5b->txntype   = 'Expenses';
        $othertxn_5b->txndetails= $request->input('trans_details_5');
        $othertxn_5b->amount    = $request->input('petrol_5');
        $othertxn_5b->updated_by= $userid; //get userid
        $othertxn_5b->save();


        $tot_diesel_coll = $othertxn_1a->amount + $othertxn_3a->amount + $othertxn_4a->amount + $othertxn_5a->amount;
        $tot_petrol_coll = $othertxn_1b->amount + $othertxn_3b->amount + $othertxn_4b->amount + $othertxn_5b->amount;
        $tot_coll = $tot_diesel_coll + $tot_petrol_coll;
        $diesel_shortage = $tot_diesel_val - $tot_diesel_coll;
        $petrol_shortage = $tot_petrol_val - $tot_petrol_coll;
        $shortage = $diesel_shortage + $petrol_shortage;
        $diesel_open_stock = $eodayid->where('companyid', '=', $companyid)->where('fueltype','=','Diesel')->where('stationid','=',$stationid)->orderBy('id', 'desc')->pluck('close_stock')->first();//get from DB
        $petrol_open_stock= $eodayid->where('companyid', '=', $companyid)->where('fueltype','=','Petrol')->where('stationid','=',$stationid)->orderBy('id', 'desc')->pluck('close_stock')->first();//get from DB
        $diesel_purchases = $request->input('diesel_purchases');
        $petrol_purchases = $request->input('petrol_purchases');
        $diesel_dip = $request->input('diesel_dip');
        $petrol_dip = $request->input('petrol_dip');
        $diesel_close_stock = $diesel_open_stock + $diesel_purchases - $tot_diesel_vol;
        $petrol_close_stock = $petrol_open_stock + $petrol_purchases - $tot_petrol_vol;

        //$value = ($condition) ? 'Truthy Value' : 'Falsey Value';

        $curr_datetime = date('Y-m-d H:i:s');
        $lasteod_datetime = Eoday::orderBy('id', 'desc')->where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->pluck('created_at')->first();

        $diesel_pos_cash_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','diesel')->where('paymethod','=','cash')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();
        $diesel_pos_mpesa_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','diesel')->where('paymethod','=','mpesa')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();
        $diesel_pos_credit_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','diesel')->where('paymethod','=','credit')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();
        $petrol_pos_cash_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','petrol')->where('paymethod','=','cash')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();
        $petrol_pos_mpesa_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','petrol')->where('paymethod','=','mpesa')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();
        $petrol_pos_credit_qry = DB::table('txns')->where('companyid', '=', $companyid)->where('fueltype','=','petrol')->where('paymethod','=','credit')->where('stationid', '=', $stationid)->where('created_at', '>', $lasteod_datetime)->where('created_at', '<=' , $curr_datetime)->groupBy('fueltype')->groupBy('paymethod')->pluck(DB::raw('sum(amount)'))->first();

        $diesel_pos_cash = isset($diesel_pos_cash_qry) ? $diesel_pos_cash_qry : 0;
        $diesel_pos_mpesa = isset($diesel_pos_mpesa_qry) ? $diesel_pos_mpesa_qry : 0;
        $diesel_pos_credit = isset($diesel_pos_credit_qry) ? $diesel_pos_credit_qry : 0;
        $petrol_pos_cash = isset($petrol_pos_cash_qry) ? $petrol_pos_cash_qry : 0;
        $petrol_pos_mpesa = isset($petrol_pos_mpesa_qry) ? $petrol_pos_mpesa_qry : 0;
        $petrol_pos_credit = isset($petrol_pos_credit_qry) ? $petrol_pos_credit_qry : 0;
        
        $diesel_pos_total = $diesel_pos_cash + $diesel_pos_mpesa + $diesel_pos_credit;
        $petrol_pos_total = $petrol_pos_cash + $petrol_pos_mpesa + $petrol_pos_credit;

        $eoday = new Eoday;
        $eoday->stationid = $stationid;
        $eoday->companyid = $companyid;
        $eoday->fueltype = "Diesel";
        $eoday->tot_vol = $tot_diesel_vol;
        $eoday->rate = $diesel_rate;
        $eoday->tot_val = $tot_diesel_val;
        $eoday->tot_coll = $tot_diesel_coll;
        $eoday->shortage = $diesel_shortage;
        $eoday->open_stock = $diesel_open_stock;
        $eoday->purchases = $diesel_purchases;
        $eoday->dip = $diesel_dip;
        $eoday->close_stock = $diesel_close_stock;
        $eoday->banked = $othertxn_1a->amount;
        $eoday->mpesa = $othertxn_3a->amount;
        $eoday->credit = $othertxn_4a->amount;
        $eoday->expenses = $othertxn_5a->amount;
        $eoday->pos_cash = $diesel_pos_cash;
        $eoday->pos_mpesa = $diesel_pos_mpesa;
        $eoday->pos_credit = $diesel_pos_credit;
        $eoday->pos_total = $diesel_pos_total;
        $eoday->save();

        $eoday = new Eoday;
        $eoday->stationid = $stationid;
        $eoday->companyid = $companyid;
        $eoday->fueltype = "Petrol";
        $eoday->tot_vol = $tot_petrol_vol;
        $eoday->rate = $petrol_rate;
        $eoday->tot_val = $tot_petrol_val;
        $eoday->tot_coll = $tot_petrol_coll;
        $eoday->shortage = $petrol_shortage;
        $eoday->open_stock = $petrol_open_stock;
        $eoday->purchases = $petrol_purchases;
        $eoday->dip = $petrol_dip;
        $eoday->close_stock = $petrol_close_stock;
        $eoday->banked = $othertxn_1b->amount;
        $eoday->mpesa = $othertxn_3b->amount;
        $eoday->credit = $othertxn_4b->amount;
        $eoday->expenses = $othertxn_5b->amount;
        $eoday->pos_cash = $petrol_pos_cash;
        $eoday->pos_mpesa = $petrol_pos_mpesa;
        $eoday->pos_credit = $petrol_pos_credit;
        $eoday->pos_total = $petrol_pos_total;
        $eoday->save();

        return redirect('/eodays')->with(['success'=>'Eoday Created']);
    }

}

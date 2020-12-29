<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Eoday;
use App\Reading;
use App\Company;
use App\Rate;
use App\Station;
use App\User;
use App\Pump;
use App\Shift;
use App\Tank;
use App\Pumpreading;
use App\Pumpshift;
use App\Tankreading;
use App\Tankshift;
use App\Actualcollection;
use App\Othersale;
use App\Txn;
use App\Product;
use App\Vehcollection;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Auth;
use Excel;
Use PDF;
use Carbon\Carbon;

class EodaysController extends Controller
{
    //remove
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        if (Auth::user()->usertype == 'stationadmin'){
            $eodays = Eoday::where('companyid', '=', $companyid)->where('stationid','=', $stationid)->orderBy('created_at','desc')->paginate(7);
            return View('eodays.index')->with('eodays', $eodays);
        }
        $eodays = Eoday::where('companyid', '=', $companyid)->orderBy('created_at','desc')->paginate(7);
        return View('eodays.index')->with('eodays', $eodays);
    }

    //remove
    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $eoday =  Eoday::where('companyid', '=', $companyid)->find($id);
        $othertxns = DB::table('othertxns')->where('companyid', '=', $companyid)->where('eoday_id','=', $id)->get();
        $id_readings =  DB::table('readings')->where('companyid', '=', $companyid)->where('eoday_id','=', $id)->get();
        return view('eodays.show',['eoday'=> $eoday, 'othertxns' => $othertxns, 'id_readings' => $id_readings]);
    }

    // remove
    public function newcreate()
    {
        $companyid = Auth::user()->companyid;
        // $lastshift = Shift::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->orderBy('id', 'DESC');
        // $lastshift_id = $lastshift->select('id')->pluck('id')->first();
        // $lastshift_det = $lastshift->select('id', 'date', 'shift')->first();
        // $pumpreadings = Pumpreading::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->where('shift_id', '=', $lastshift_id)->select('pump_id', 'reading')->get();
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        // $shift_date = $lastshift_det->date;
        // $shift = $lastshift_det->shift;
        // $shift_id = $lastshift_det->id;
        // $attendants = User::where('companyid', '=', $companyid)->where('usertype','=','attendant')->pluck('fullname', 'id')->all();
        // $shift_id = 7;
        // $dt = Carbon::createFromFormat('Y-m-d', $shift_date);
        // if ($shift == 1) 
        // {
        //     $shift = 2;
        // }
        // else 
        // {
        //     $shift_date = $dt->addDay()->format('Y-m-d');
        //     $shift = 1;
        // }
        

        // return View('eodays.create', ['attendants' => $attendants, 'stations' => $stations, 'shift_id' => $shift_id, 'shift_date' => $shift_date, 'shift' => $shift, 'pumpreadings' => $pumpreadings]);

        return View('eodays.create', ['stations' => $stations]);
    }

    public function neweodentry(Request $request)
    {
        $companyid = Auth::user()->companyid;
        
        $stationid = $request->input('stationid');  
        $station = Station::where('companyid', '=', $companyid)->where('id', '=', $stationid)->select('station')->pluck('station')->first();
        
        //check if there is an existing shift for this station
        $shiftquery = Shift::where('company_id', '=', $companyid)->where('station_id', '=', $stationid);
        $shiftexists = $shiftquery->count();
        if ($shiftexists == 0)
        {
            $pumps = Pump::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->select('id', 'pumpname', 'fueltype')->get();
            $tanks = Tank::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->select('id', 'tankname', 'fueltype')->get();
            $shift_date = Carbon::now()->format('Y-m-d');
            $shift = '1';
        }
        else 
        {   
            $lastshift = $shiftquery->orderBy('id', 'desc')->select('id', 'date', 'shift')->first();
            $shift_date = $lastshift->date;
            $shift = $lastshift->shift;
            $dt = Carbon::createFromFormat('Y-m-d', $shift_date);
            if ($shift == 1) 
            {
                $shift = 2;
            }
            else 
            {
                $shift_date = $dt->addDay()->format('Y-m-d');
                $shift = 1;
            }

            $pumps = Pump::leftJoin('pumpreadings', 'pumps.id', '=', 'pumpreadings.pump_id')->where('pumps.companyid', '=', $companyid)->where('pumps.stationid', '=', $stationid)->where(function ($query) use($lastshift) { $query->where('pumpreadings.shift_id', '=', $lastshift->id)->orWhereNull('pumpreadings.shift_id'); })->select('pumps.id', 'pumps.pumpname', 'pumps.fueltype', 'pumpreadings.reading')->orderBy('pumps.pumpname', 'asc')->get();
            $tanks = Tank::join('tankreadings', 'tanks.id', '=', 'tankreadings.tank_id')->where('tanks.companyid', '=', $companyid)->where('tanks.stationid', '=', $stationid)->where(function ($query) use($lastshift) { $query->where('tankreadings.shift_id', '=', $lastshift->id)->orWhereNull('tankreadings.shift_id'); })->select('tanks.id', 'tanks.tankname', 'tankreadings.reading')->orderBy('tanks.tankname', 'asc')->get();
        }

        $products = Product::where('company_id', '=', $companyid)->select('id', 'name')->get();

        //get fuel rates
        $rates = Rate::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->where('start_rate_date', '<=', $shift_date )->where('end_rate_date', '>=', $shift_date )->pluck('sellprice', 'fueltype')->toArray();
        // $diesel_rate = $rates['diesel'];
        // $petrol_rate = $rates['petrol'];
        // $kerosene_rate = $rates['kerosene'];

        if (!(array_key_exists('diesel', $rates)) || !(array_key_exists('petrol', $rates)) || !(array_key_exists('kerosene', $rates)) )
        {
            return redirect('/eodays/new/create')->with('error', 'Please ask the administrator to update ALL rates first (diesel, petrol and kerosene)'    );
        }
        
        //Check attendants with transations
        $attendants = Txn::join('users', 'txns.userid', '=', 'users.id' )->where('txns.companyid', '=', $companyid)->where('txns.stationid', '=', $stationid)->where(DB::raw('date(txns.created_at)'),'>=',$shift_date)->select('txns.userid as userid', 'users.fullname as fullname')->distinct()->pluck('fullname', 'userid')->sortBy('users.fullname')->toArray();

        // what happens if not trasactions are recorded = show all attendants
        if (empty($attendants)){
            $attendants = User::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->pluck('fullname', 'id')->sortBy('fullname')->toArray();
        }

        //show credit collections
        $creditcoll = Vehcollection::where('company_id', '=', $companyid)->where(DB::raw('date(created_at)'),'=', $shift_date)->get();
                
        return View('eodays.eodentry', ['stationid' => $stationid, 'station' => $station, 'shift_date' => $shift_date, 'shift' => $shift, 'pumps' => $pumps, 'tanks' => $tanks, 'attendants' => $attendants, 'rates' => $rates, 'products' => $products, 'creditcoll' => $creditcoll]);
    }

    public function posteodentry(Request $request)
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        $userid = $user->id;

        $this->validate($request, [
            '*' => 'bail|required'
        ]);

        $stationid = $request->input('stationid');
        $shift_date = $request->input('shift_date');
        $shift = $request->input('shift');
        $attendants = $request->input('attendants');

        //get fuel rates
        $rates = Rate::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->where('start_rate_date', '<=', $shift_date )->where('end_rate_date', '>=', $shift_date )->pluck('sellprice', 'fueltype')->toArray();
        // $diesel_rate = $rates['diesel'];
        // $petrol_rate = $rates['petrol'];
        // $kerosene_rate = $rates['kerosene'];

        if (!(array_key_exists('diesel', $rates)) || !(array_key_exists('petrol', $rates)) || !(array_key_exists('kerosene', $rates)) )
        {
            return redirect('/eodays/new/create')->with('error', 'Please ask the administrator to update ALL rates first (diesel, petrol and kerosene) D, P, K '    );
        }
        
        DB::transaction(function () use($stationid, $companyid, $userid, $shift_date, $shift, $request, $rates) {
            //Save shift details
            $new_shift = new Shift;
            $new_shift->station_id = $stationid;
            $new_shift->company_id = $companyid;
            $new_shift->date = $shift_date;
            $new_shift->shift = $shift;
            $new_shift->save();

            // $diesel_rate = $rates['diesel'];
            // $petrol_rate = $rates['petrol'];
            // $kerosene_rate = $rates['kerosene'];

            //Check pumps with transactions
            $pumps = Pump::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->select('id', 'pumpname', 'fueltype')->get();

            //Check attendants with transations
            $attendants = Txn::join('users', 'txns.userid', '=', 'users.id' )->where('txns.companyid', '=', $companyid)->where('txns.stationid', '=', $stationid)->where(DB::raw('date(txns.created_at)'),'>=',$shift_date)->select('txns.userid as userid', 'users.fullname as fullname')->distinct()->pluck('fullname', 'userid')->toArray();
            // what happens if not trasactions are recorded = show all attendants
            if (empty($attendants)){
                $attendants = User::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->pluck('fullname', 'id')->toArray();
            }

            //Check the tanks
            $tanks = Tank::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->select('id', 'tankname')->get();

            // Check the products
            $products = Product::where('company_id', '=', $companyid)->select('id', 'name')->get();

            //Save actual collections
            foreach ($attendants  as $id => $att) 
            {
                $attid = $request->input('attid_'.$id); // get from db
                ${'attcash_'.$id} = $request->input('attcash_'.$id);
                ${'attmpesa_'.$id} = $request->input('attmpesa_'.$id);
                ${'attcredit_'.$id} = $request->input('attcredit_'.$id);
                ${'attvisa_'.$id} =$request->input('attvisa_'.$id);
                ${'atttotal_'.$id} = ${'attcash_'.$id} + ${'attmpesa_'.$id} + ${'attcredit_'.$id} + ${'attvisa_'.$id};

                ${'coll_'.$id} = new Actualcollection;
                ${'coll_'.$id}->shift_id = $new_shift->id;
                ${'coll_'.$id}->date = $shift_date;
                ${'coll_'.$id}->shift = $shift;
                ${'coll_'.$id}->company_id = $companyid;
                ${'coll_'.$id}->station_id = $stationid;
                ${'coll_'.$id}->attendant_id = $attid; // rem to change
                ${'coll_'.$id}->cash = ${'attcash_'.$id};
                ${'coll_'.$id}->mpesa = ${'attmpesa_'.$id};
                ${'coll_'.$id}->credit = ${'attcredit_'.$id};
                ${'coll_'.$id}->visa = ${'attvisa_'.$id};
                ${'coll_'.$id}->total = ${'atttotal_'.$id};
                ${'coll_'.$id}->updated_by = $userid;
                ${'coll_'.$id}->save();
            }

            //Save other sales 
            foreach ($products as $prod)
            {
                ${'itemcash_'.$prod['id']} = $request->input('itemcash_'.$prod['id']);
                ${'itemmpesa_'.$prod['id']} = $request->input('itemmpesa_'.$prod['id']);
                ${'itemcredit_'.$prod['id']} = $request->input('itemcredit_'.$prod['id']);
                ${'itemvisa_'.$prod['id']} =$request->input('itemvisa_'.$prod['id']);
                ${'itemtotal_'.$prod['id']} = ${'itemcash_'.$prod['id']} + ${'itemmpesa_'.$prod['id']} + ${'itemcredit_'.$prod['id']} + ${'itemvisa_'.$prod['id']};

                ${'othersale_'.$prod['id']} = new Othersale;
                ${'othersale_'.$prod['id']}->shift_id = $new_shift->id;
                ${'othersale_'.$prod['id']}->date = $shift_date;
                ${'othersale_'.$prod['id']}->shift = $shift;
                ${'othersale_'.$prod['id']}->company_id = $companyid;
                ${'othersale_'.$prod['id']}->product_id = $prod['id']; 
                ${'othersale_'.$prod['id']}->cash = ${'itemcash_'.$prod['id']};
                ${'othersale_'.$prod['id']}->mpesa = ${'itemmpesa_'.$prod['id']};
                ${'othersale_'.$prod['id']}->credit = ${'itemcredit_'.$prod['id']};
                ${'othersale_'.$prod['id']}->visa = ${'itemvisa_'.$prod['id']};
                ${'othersale_'.$prod['id']}->total = ${'itemtotal_'.$prod['id']};
                ${'othersale_'.$prod['id']}->updated_by = $userid;
                ${'othersale_'.$prod['id']}->save();
            }

            //Save pump readings
            foreach ($pumps as $pump)
            {
                if ($pump['fueltype'] == 'Diesel')
                {
                    $unitprice = $rates['diesel'];
                }
                else if ($pump['fueltype'] == 'Petrol')
                {
                    $unitprice = $rates['petrol'];
                }
                else if  ($pump['fueltype'] == 'Kerosene')
                {
                    $unitprice = $rates['kerosene'];
                }
                $pumpid = $request->input('pumpid_'.$pump['id']); // get from db
                ${'pumpnew_'.$pump['id']} = $request->input('pumpnew_'.$pump['id']);
                ${'pumpprev_'.$pump['id']} = $request->input('pumpprev_'.$pump['id']);
                ${'pumpatt_'.$pump['id']} = $request->input('pumpatt_'.$pump['id']);
                ${'pumpret_'.$pump['id']} = $request->input('pumpret_'.$pump['id']);
                ${'pumpsales_'.$pump['id']} = ${'pumpnew_'.$pump['id']} - ( ${'pumpprev_'.$pump['id']} + ${'pumpret_'.$pump['id']} ) ;
                // $unitprice = 100;
                ${'pumptotal_'.$pump['id']} = ${'pumpsales_'.$pump['id']} * $unitprice;

                ${'preading_'.$pump['id']} = new Pumpreading;
                ${'preading_'.$pump['id']}->pump_id    = $pumpid; 
                ${'preading_'.$pump['id']}->company_id = $companyid;
                ${'preading_'.$pump['id']}->station_id = $stationid;
                ${'preading_'.$pump['id']}->date = $shift_date;
                ${'preading_'.$pump['id']}->shift = $shift;
                ${'preading_'.$pump['id']}->shift_id = $new_shift->id;
                ${'preading_'.$pump['id']}->reading = ${'pumpnew_'.$pump['id']};
                ${'preading_'.$pump['id']}->attendant_id = ${'pumpatt_'.$pump['id']} ;
                ${'preading_'.$pump['id']}->save();
                
                ${'pumpshift_'.$pump['id']} = new Pumpshift;
                ${'pumpshift_'.$pump['id']}->shift_id = $new_shift->id;
                ${'pumpshift_'.$pump['id']}->date = $shift_date;
                ${'pumpshift_'.$pump['id']}->shift = $shift;
                ${'pumpshift_'.$pump['id']}->fuel_type = $pump['fueltype']; 
                ${'pumpshift_'.$pump['id']}->company_id = $companyid;
                ${'pumpshift_'.$pump['id']}->station_id = $stationid;
                ${'pumpshift_'.$pump['id']}->pump_id    = $pumpid; 
                ${'pumpshift_'.$pump['id']}->opening = ${'pumpprev_'.$pump['id']};
                ${'pumpshift_'.$pump['id']}->returned = ${'pumpret_'.$pump['id']};
                ${'pumpshift_'.$pump['id']}->closing = ${'pumpnew_'.$pump['id']};
                ${'pumpshift_'.$pump['id']}->sales = ${'pumpsales_'.$pump['id']};
                ${'pumpshift_'.$pump['id']}->unitprice = $unitprice;
                ${'pumpshift_'.$pump['id']}->total = ${'pumptotal_'.$pump['id']};
                ${'pumpshift_'.$pump['id']}->attendant_id = ${'pumpatt_'.$pump['id']} ;
                ${'pumpshift_'.$pump['id']}->save();
            }
            // Need new table for attendant

            // Need new table for station

            //Save tank readings
            foreach ($tanks as $tank)
            {
                $tankid = $request->input('tankid_'.$tank['id']); // to get from db
                ${'tanknew_'.$tank['id']} = $request->input('tanknew_'.$tank['id']); //new reading
                ${'tankprev_'.$tank['id']} = $request->input('tankprev_'.$tank['id']); //prev reading
                ${'tankpurc_'.$tank['id']} = $request->input('tankpurc_'.$tank['id']);
                ${'tanksold_'.$tank['id']} = ( ${'tankprev_'.$tank['id']} + ${'tankpurc_'.$tank['id']} ) - ${'tanknew_'.$tank['id']}  ;

                ${'treading_'.$tank['id']} = new Tankreading;
                ${'treading_'.$tank['id']}->company_id = $companyid;
                ${'treading_'.$tank['id']}->station_id = $stationid;
                ${'treading_'.$tank['id']}->shift_id = $new_shift->id;
                ${'treading_'.$tank['id']}->date = $shift_date;
                ${'treading_'.$tank['id']}->shift = $shift;
                ${'treading_'.$tank['id']}->reading = ${'tanknew_'.$tank['id']};
                ${'treading_'.$tank['id']}->tank_id    = $tankid;
                ${'treading_'.$tank['id']}->save();

                ${'tankshift_'.$tank['id']} = new Tankshift;
                ${'tankshift_'.$tank['id']}->shift_id = $new_shift->id;
                ${'tankshift_'.$tank['id']}->date = $shift_date;
                ${'tankshift_'.$tank['id']}->shift = $shift;
                ${'tankshift_'.$tank['id']}->company_id = $companyid;
                ${'tankshift_'.$tank['id']}->station_id = $stationid;
                ${'tankshift_'.$tank['id']}->tank_id    = $tankid; // to change
                ${'tankshift_'.$tank['id']}->opening = ${'tankprev_'.$tank['id']};
                ${'tankshift_'.$tank['id']}->purchased = ${'tankpurc_'.$tank['id']};
                ${'tankshift_'.$tank['id']}->closing = ${'tanknew_'.$tank['id']};
                ${'tankshift_'.$tank['id']}->sold = ${'tanksold_'.$tank['id']};
                ${'tankshift_'.$tank['id']}->save();
            }
        });

        return redirect('/eodays/daily/index')->with(['success'=> 'Eoday created successfully']);
    }

    public function listeodentry()
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        $userid = $user->id;

        $shifts = Shift::where('company_id', '=', $companyid)->orderBy('created_at','desc')->paginate(10);

        return View('eodays.daily.index', ['shifts' => $shifts]);
    }

    public function showeodentry(Request $request, $id)
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        $company_details = Company::where('id', '=', $companyid)->first();
        $userid = $user->id;
        $curr_date = Carbon::now()->format('d-m-Y');

        //shift details
        $shift = Shift::where('company_id', '=', $companyid)->where('id', '=', $id)->first();

        //pump shift per fuel type
        //show tot vol and sales per fuel type
        //show tot vol and sales
        $pumpshift = Pumpshift::where('company_id', '=', $companyid)->where('shift_id', '=', $id)->get();
        $pumpsumm = Pumpshift::where('company_id', '=', $companyid)->select('fuel_type', DB::raw('sum(sales) as tot_vol'), DB::raw('sum(total) as tot_sales'))->where('shift_id', '=', $id)->groupBy('fuel_type')->get();
        $pumptots = ['diesel_vol' => 0, 'petrol_vol' => 0, 'kerosene_vol' => 0, 'diesel_sales' => 0, 'petrol_sales' => 0, 'kerosene_sales' => 0, 'total_vol' => 0, 'total_sales' => 0];
        foreach ($pumpsumm as $summ){
            if ($summ['fuel_type'] == 'Diesel'){
                $pumptots['diesel_vol'] = $summ['tot_vol'];
                $pumptots['diesel_sales'] = $summ['tot_sales'];
            }
            if ($summ['fuel_type'] == 'Petrol'){
                $pumptots['petrol_vol'] = $summ['tot_vol'];
                $pumptots['petrol_sales'] = $summ['tot_sales'];
            }
            if ($summ['fuel_type'] == 'Kerosene'){
                $pumptots['kerosene_vol'] = $summ['tot_vol'];
                $pumptots['kerosene_sales'] = $summ['tot_sales'];
            }
        }
        $pumptots['total_sales'] = $pumptots['diesel_sales'] + $pumptots['petrol_sales'] + $pumptots['kerosene_sales'];
        $pumptots['total_vol'] = $pumptots['diesel_vol'] + $pumptots['petrol_vol'] + $pumptots['kerosene_vol'];

        $pumpatt = Pumpshift::where('company_id', '=', $companyid)->select('attendant_id', DB::raw('sum(total) as tot_sales'))->where('shift_id', '=', $id)->groupBy('attendant_id')->get();
        
        //show tot purchases and sales
        $tankshift = Tankshift::where('company_id', '=', $companyid)->where('shift_id', '=', $id)->get();
        $tanksumm = Tankshift::where('company_id', '=', $companyid)->where('shift_id', '=', $id)->select(DB::raw('sum(purchased) as tot_purchased'), DB::raw('sum(sold) as tot_sold'))->first();

        //show tot per channel and total
        $actcoll = Actualcollection::where('company_id', '=', $companyid)->where('shift_id', '=', $id)->get();
        $actsumm = Actualcollection::where('company_id', '=', $companyid)->select(DB::raw('sum(cash) as tot_cash'), DB::raw('sum(mpesa) as tot_mpesa'), DB::raw('sum(credit) as tot_credit'), DB::raw('sum(visa) as tot_visa'), DB::raw('sum(total) as tot_total'))->where('shift_id', '=', $id)->first();
        
        //get total sales per attendant as per txns table
        $poscoll = Txn::where('companyid', '=', $companyid)->select('userid', 'paymethod', DB::raw('sum(amount) as tot_amount'))->where(DB::raw('date(created_at)'), '=', $shift->date)->groupBy('userid')->groupBy('paymethod')->get();

        //show tot for other products
        $othersale = Othersale::where('company_id', '=', $companyid)->where('shift_id', '=', $id)->get();
        $othersumm = Othersale::where('company_id', '=', $companyid)->select(DB::raw('sum(cash) as tot_cash'), DB::raw('sum(mpesa) as tot_mpesa'), DB::raw('sum(credit) as tot_credit'), DB::raw('sum(visa) as tot_visa'), DB::raw('sum(total) as tot_total'))->where('shift_id', '=', $id)->first();

        //calculate shortages
        $short = [];
        $res = [];
        foreach ($pumpatt as $patt)
        {
            foreach ($actcoll as $acoll)
            {
                if ($patt['attendant_id'] == $acoll['attendant_id'])
                {
                    $short['attendant_id'] = $patt['attendant_id'];
                    $short['attendant_name'] = User::select('fullname')->where('id', '=', $patt['attendant_id'])->pluck('fullname')->first();
                    $short['amount'] = $acoll['total'] - $patt['tot_sales'];
                    $res[] = $short;
                    // $shortage[$patt['attendant_id']] = $acoll['total'] - $patt['tot_sales'];
                }
            }
        }
        $shortage = collect($res);
        $tot_short = $actsumm['tot_total'] - $pumptots['total_sales'];

        //show credit collections
        $creditcoll = Vehcollection::where('company_id', '=', $companyid)->where(DB::raw('date(created_at)'),'=', $shift->date)->get();

        if ($request->submitBtn == 'DownloadRpt') {
            $pdf = PDF::loadView('pdf.eodreport', ['company_details' => $company_details, 'shift' => $shift, 'pumpshift' => $pumpshift, 'tankshift' => $tankshift, 'tanksumm' => $tanksumm, 'actcoll' => $actcoll, 'pumptots' => $pumptots, 'pumpatt' => $pumpatt, 'actsumm' => $actsumm, 'shortage' => $shortage, 'tot_short' => $tot_short, 'poscoll' => $poscoll, 'othersale' => $othersale, 'othersumm' => $othersumm, 'curr_date' => $curr_date, 'creditcoll' => $creditcoll]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('shiftreport.pdf');
        } 

        return View('eodays.daily.show', ['shift' => $shift,'pumpshift' => $pumpshift, 'tankshift' => $tankshift, 'tanksumm' => $tanksumm, 'actcoll' => $actcoll, 'pumptots' => $pumptots, 'pumpatt' => $pumpatt, 'actsumm' => $actsumm, 'shortage' => $shortage, 'tot_short' => $tot_short, 'poscoll' => $poscoll, 'othersale' => $othersale, 'othersumm' => $othersumm, 'creditcoll' => $creditcoll]);
    }

    public function vehiclesrpt(Request $request){

        $companyid = Auth::user()->companyid;
        $dt = Carbon::now();

        $monthdt = $request->input('month');

        if ($monthdt != NULL)
        {
            $month = Carbon::parse($monthdt);
            $daysinmonth = $month->daysInMonth;
        }
        else
        {
            $month = $dt->format('Y-m');
            $month = Carbon::parse($month);
            $monthdt = $dt->format('Y-m');
            $daysinmonth = $dt->daysInMonth;   
        }

        $startmon = $month->startOfMonth();
        $startmondt = $startmon->format('Y-m-d');

        $statement = "owners.own_num, owners.fullname, txns.vehregno, sum(if(date(txns.created_at) = '$startmondt', txns.volume, 0)) as '$startmondt'";
        for ($i = 1; $i < $daysinmonth; $i++) {
            $stdt = $startmon->addDays(1);
            $stdtformatted = $stdt->format('Y-m-d');
            $statement = $statement.", sum(if(date(txns.created_at) = '$stdtformatted', txns.volume, 0)) as '$stdtformatted'";
        }
        $statement = $statement . ", sum(txns.volume) as 'total'";

        $vehreport = Txn::join('owners', 'txns.ownerid', '=', 'owners.id')->select(DB::raw("$statement"))->where(DB::raw('DATE_FORMAT(txns.created_at, "%Y-%m")'), '=', "$monthdt")->where('txns.ownerid', '!=', '0')->where('txns.companyid', '=', '3')->groupBy('txns.vehregno')->orderBy('txns.ownerid', 'asc')->get();

        if ($request->submitBtn == 'DownloadExcel') {
            $vehreport = $vehreport->toArray();
            return Excel::create('vehreport_details', function($excel) use ($vehreport) {
                $excel->sheet('vehreport', function($sheet) use ($vehreport)
                {
                    $sheet->fromArray($vehreport, null, 'A1', true);
                    $sheet->prependRow(array('NSL Sacco'));
                    // $sheet->row(1, array('NSL Sacco'));
                    // $sheet->rows(array(array('test1', 'test2'),array('test3', 'test4')));
                    $sheet->setFreeze('D3');
                });
            })->download('xlsx');
        } 

        return view('reports.vehicles', ['daysinmonth' => $daysinmonth, 'startmon' => $startmon,'startmondt' => $startmondt, 'vehreport' => $vehreport]);
    }

    public function monthlyrpt(Request $request){

        $companyid = Auth::user()->companyid;
        $curr_date = date('Y').'-'.date('m');
        $stationid = Auth::user()->stationid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id');

        if (Auth::user()->usertype == 'stationadmin'){
            if ($request->isMethod('POST')){
                $this->validate($request, [
                    'fueltype' => 'required',
                    'month' => 'required'
                ]);

                $fueltype = $request->input('fueltype');
                $month = $request->input('month');

                $monthlyrpts = Eoday::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->where('fueltype', '=', $fueltype)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month)->get();
                return view('reports.monthly.index', ['monthlyrpts' => $monthlyrpts, 'stations' => $stations]);
            } 

            $monthlyrpts = Eoday::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $curr_date)->get();
            return view('reports.monthly.index', ['monthlyrpts' => $monthlyrpts, 'stations' => $stations]);
        }

        if ($request->isMethod('POST')){
            $this->validate($request, [
                'fueltype' => 'required',
                'month' => 'required'
            ]);

            $fueltype = $request->input('fueltype');
            $month = $request->input('month');
            $stationid = $request->input('station');

            $monthlyrpts = Eoday::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->where('fueltype', '=', $fueltype)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $month)->get();
            return view('reports.monthly.index', ['monthlyrpts' => $monthlyrpts, 'stations' => $stations]);
        } 

        $monthlyrpts = Eoday::where('companyid', '=', $companyid)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), '=', $curr_date)->get();
        return view('reports.monthly.index', ['monthlyrpts' => $monthlyrpts, 'stations' => $stations]);
    }

    public function downloadeodayExcel($type)
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        if (Auth::user()->usertype == 'stationadmin'){
    	    $data = Eoday::where('companyid', '=', $companyid)->where('stationid','=', $stationid)->get()->toArray();
        } 
        else if (Auth::user()->usertype == 'admin'){
            $data = Eoday::where('companyid', '=', $companyid)->get()->toArray();
        }
		return Excel::create('eoday_details', function($excel) use ($data) {
			$excel->sheet('eoday', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

}

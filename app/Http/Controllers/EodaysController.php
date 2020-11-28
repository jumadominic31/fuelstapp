<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Eoday;
use App\Reading;
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
use App\Txn;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Auth;
use Excel;
use Carbon\Carbon;

class EodaysController extends Controller
{

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

    public function pumpcreate()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $lastshift = Shift::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->orderBy('id', 'DESC');
        $lastshift_id = $lastshift->select('id')->pluck('id')->first();
        $lastshift_det = $lastshift->select('id', 'date', 'shift')->first();
        $pumpreadings = Pumpreading::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->where('shift_id', '=', $lastshift_id)->select('pump_id', 'reading')->get();
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        $shift_date = $lastshift_det->date;
        $shift = $lastshift_det->shift;
        $shift_id = $lastshift_det->id;
        $attendants = User::where('companyid', '=', $companyid)->where('usertype','=','attendant')->pluck('fullname', 'id')->all();
        // $shift_id = 7;
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
        

        return View('eodays.pump', ['attendants' => $attendants, 'stations' => $stations, 'shift_id' => $shift_id, 'shift_date' => $shift_date, 'shift' => $shift, 'pumpreadings' => $pumpreadings]);
    }

    public function pumpstore(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;

        $shift_date = $request->input('shift_date');
        $shift_id = $request->input('shift_id');
        $shift = $request->input('shift');

        // shoud only run if all input exit
        $this->validate($request, [
            '*' => 'required',
            'stationid' => 'nullable'
        ]);

        $new_shift = new Shift;
        $new_shift->station_id = $stationid;
        $new_shift->company_id = $companyid;
        $new_shift->date = $shift_date;
        $new_shift->shift = $shift;
        $new_shift->save();
        

        $pumps = Pump::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->pluck('pumpname', 'id')->toArray();

        foreach ($pumps as $id => $pump) {
            $this->validate($request, [
                'new_'.$id => 'required|numeric',
                'att_'.$id => 'required|numeric',
                'returned_'.$id => 'required|numeric'
            ]);

            ${'new_'.$id} = $request->input('new_'.$id);
            ${'prev_'.$id} = $request->input('prev_'.$id);
            ${'att_'.$id} = $request->input('att_'.$id);
            ${'returned_'.$id} = $request->input('returned_'.$id);
            ${'sales_'.$id} = ${'new_'.$id} - ( ${'prev_'.$id} + ${'returned_'.$id} ) ;
            $unitprice = 100;
            ${'total_'.$id} = ${'sales_'.$id} * $unitprice;

            ${'reading_'.$id} = new Pumpreading;
            ${'reading_'.$id}->pump_id    = $id;
            ${'reading_'.$id}->company_id = $companyid;
            ${'reading_'.$id}->station_id = $stationid;
            ${'reading_'.$id}->date = $shift_date;
            ${'reading_'.$id}->shift = $shift;
            ${'reading_'.$id}->shift_id = $new_shift->id;
            ${'reading_'.$id}->reading = ${'new_'.$id};
            ${'reading_'.$id}->attendant_id = ${'att_'.$id} ;
            ${'reading_'.$id}->save();

            ${'pumpshift_'.$id} = new Pumpshift;
            ${'pumpshift_'.$id}->shift_id = $new_shift->id;
            ${'pumpshift_'.$id}->date = $shift_date;
            ${'pumpshift_'.$id}->shift = $shift;
            ${'pumpshift_'.$id}->company_id = $companyid;
            ${'pumpshift_'.$id}->station_id = $stationid;
            ${'pumpshift_'.$id}->pump_id    = $id;
            ${'pumpshift_'.$id}->opening = ${'prev_'.$id};
            ${'pumpshift_'.$id}->returned = ${'returned_'.$id};
            ${'pumpshift_'.$id}->closing = ${'new_'.$id};
            ${'pumpshift_'.$id}->sales = ${'sales_'.$id};
            ${'pumpshift_'.$id}->unitprice = $unitprice;
            ${'pumpshift_'.$id}->total = ${'total_'.$id};
            ${'pumpshift_'.$id}->attendant_id = ${'att_'.$id} ;
            ${'pumpshift_'.$id}->save();

        }

        return redirect('/eodays/pump/create')->with(['success'=>'Eoday Created']);
    }

    public function tankcreate()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        $lastshift = Shift::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->orderBy('id', 'DESC');
        $lastshift_id = $lastshift->select('id')->pluck('id')->first();
        $lastshift_det = $lastshift->select('id', 'date', 'shift')->first();
        $shift_date = $lastshift_det->date;
        $shift = $lastshift_det->shift;
        $shift_id = $lastshift_det->id;
        $tankreadings = Tankreading::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->where('shift_id', '=', $lastshift_id)->select('tank_id', 'reading')->get();
        // $tanks = Tank::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->pluck('tankname', 'id')->all();

        return View('eodays.tank', ['stations' => $stations, 'shift_id' => $shift_id, 'shift_date' => $shift_date, 'shift' => $shift, 'tankreadings' => $tankreadings]);
    }

    public function tankstore(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;

        $shift_date = $request->input('shift_date');
        $shift_id = $request->input('shift_id');
        $shift = $request->input('shiftid');

        // shoud only run if all input exit
        $this->validate($request, [
            '*' => 'required',
            'stationid' => 'nullable'
        ]);
        
        $tanks = Tank::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->pluck('tankname', 'id')->toArray();
        foreach ($tanks as $id => $tank) {
           
            ${'new_'.$id} = $request->input('new_'.$id); //new reading
            ${'prev_'.$id} = $request->input('prev_'.$id); //prev reading
            ${'purc_'.$id} = $request->input('purc_'.$id);
            ${'sold_'.$id} = ( ${'prev_'.$id} + ${'purc_'.$id} ) - ${'new_'.$id}  ;

            ${'reading_'.$id} = new Tankreading;
            ${'reading_'.$id}->company_id = $companyid;
            ${'reading_'.$id}->station_id = $stationid;
            ${'reading_'.$id}->shift_id = $shift_id;
            ${'reading_'.$id}->date = $shift_date;
            ${'reading_'.$id}->shift = $shift;
            ${'reading_'.$id}->reading = ${'new_'.$id};
            ${'reading_'.$id}->tank_id    = $id;
            ${'reading_'.$id}->save();

            ${'tankshift_'.$id} = new Tankshift;
            ${'tankshift_'.$id}->shift_id = $shift_id;
            ${'tankshift_'.$id}->date = $shift_date;
            ${'tankshift_'.$id}->shift = $shift;
            ${'tankshift_'.$id}->company_id = $companyid;
            ${'tankshift_'.$id}->station_id = $stationid;
            ${'tankshift_'.$id}->tank_id    = $id;
            ${'tankshift_'.$id}->opening = ${'prev_'.$id};
            ${'tankshift_'.$id}->purchased = ${'purc_'.$id};
            ${'tankshift_'.$id}->closing = ${'new_'.$id};
            ${'tankshift_'.$id}->sold = ${'sold_'.$id};
            ${'tankshift_'.$id}->save();

        }

        return redirect('/eodays/tank/create')->with(['success'=>'Eoday Created']);
    }

    public function collectioncreate()
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        $lastshift = Shift::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->orderBy('id', 'DESC');
        $lastshift_id = $lastshift->select('id')->pluck('id')->first();
        $lastshift_det = $lastshift->select('id', 'date', 'shift')->first();
        $shift_date = $lastshift_det->date;
        $shift = $lastshift_det->shift;
        $shift_id = $lastshift_det->id;
        $attendants = Pumpreading::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->where('shift_id', '=', $lastshift_id)->select('attendant_id')->get();

        return View('eodays.collection', ['stations' => $stations, 'shift_id' => $shift_id, 'shift_date' => $shift_date, 'shift' => $shift, 'attendants' => $attendants ]);
    }

    public function collectionstore(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $stationid = Auth::user()->stationid;
        $userid = Auth::user()->id;

        // shoud only run if all input exit
        $this->validate($request, [
            '*' => 'required',
            'stationid' => 'nullable'
        ]);

        $shift_id = $request->input('shift_id');
        $shift_date = $request->input('shift_date');
        $shift = $request->input('shiftid');

        $attendants = Pumpreading::where('company_id', '=', $companyid)->where('station_id', '=', $stationid)->where('shift_id', '=', $shift_id)->select('attendant_id')->get();
        
        foreach ($attendants as $att) {
            
            ${'cash_'.$att} = $request->input('cash_'.$att['attendant_id']);
            ${'mpesa_'.$att} = $request->input('mpesa_'.$att['attendant_id']);
            ${'credit_'.$att} = $request->input('credit_'.$att['attendant_id']);
            ${'visa_'.$att} =$request->input('visa_'.$att['attendant_id']);
            ${'total_'.$att} = ${'cash_'.$att} + ${'mpesa_'.$att} + ${'credit_'.$att} + ${'visa_'.$att};

            ${'coll_'.$att['attendant_id']} = new Actualcollection;
            ${'coll_'.$att['attendant_id']}->shift_id = $shift_id;
            ${'coll_'.$att['attendant_id']}->date = $shift_date;
            ${'coll_'.$att['attendant_id']}->shift = $shift;
            ${'coll_'.$att['attendant_id']}->company_id = $companyid;
            ${'coll_'.$att['attendant_id']}->station_id = $stationid;
            ${'coll_'.$att['attendant_id']}->attendant_id = $att;
            ${'coll_'.$att['attendant_id']}->cash = ${'cash_'.$att};
            ${'coll_'.$att['attendant_id']}->mpesa = ${'mpesa_'.$att};
            ${'coll_'.$att['attendant_id']}->credit = ${'credit_'.$att};
            ${'coll_'.$att['attendant_id']}->visa = ${'visa_'.$att};
            ${'coll_'.$att['attendant_id']}->total = ${'total_'.$att};
            ${'coll_'.$att['attendant_id']}->updated_by = $userid;
            ${'coll_'.$att['attendant_id']}->save();

        }

        return redirect('/eodays/collection/create')->with(['success'=>'Eoday Created']);
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $eoday =  Eoday::where('companyid', '=', $companyid)->find($id);
        $othertxns = DB::table('othertxns')->where('companyid', '=', $companyid)->where('eoday_id','=', $id)->get();
        $id_readings =  DB::table('readings')->where('companyid', '=', $companyid)->where('eoday_id','=', $id)->get();
        return view('eodays.show',['eoday'=> $eoday, 'othertxns' => $othertxns, 'id_readings' => $id_readings]);
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

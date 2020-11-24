<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Eoday;
use App\Reading;
use App\Rate;
use App\Station;
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

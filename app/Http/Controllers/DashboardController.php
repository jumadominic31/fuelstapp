<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Station;
use App\Pump;
use App\Txn;
use App\User;
use Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $curr_date = date('y').'-'.date('m').'-'.date('d');
        $companyid = Auth::user()->companyid;

        if (Auth::user()->usertype == 'stationadmin'){
            $station = Auth::user();
            $stationid = Auth::user()->stationid;
            $pumps_cnt = Pump::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->count();
            $attendants_cnt = User::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->where('usertype','=','attendant')->count();
            $total_sales = Txn::where('companyid', '=', $companyid)->where('stationid', '=', $stationid)->where(DB::raw('date(created_at)'), '=', $curr_date)->sum('amount');

            $attendant_sales = Txn::select('userid', DB::raw('sum(amount) as total_sales'))->where('companyid', '=', $companyid)->where(DB::raw('date(created_at)'), '=', $curr_date)->where('stationid', '=', $stationid)->groupBy('userid')->get();

            return view('dashboard.stationadm', ['station' => $station, 'pumps_cnt' => $pumps_cnt, 'attendants_cnt' => $attendants_cnt, 'total_sales' => $total_sales, 'attendant_sales' => $attendant_sales]);

        }

        $stations_cnt = Station::where('companyid', '=', $companyid)->count();
        $pumps_cnt = Pump::where('companyid', '=', $companyid)->count();
        $attendants_cnt = User::where('companyid', '=', $companyid)->where('usertype','=','attendant')->count();
        $total_sales = Txn::where('companyid', '=', $companyid)->where(DB::raw('date(created_at)'), '=', $curr_date)->sum('amount');

        $station_sales = Txn::select('stationid', DB::raw('sum(amount) as total_sales'))->where('companyid', '=', $companyid)->where(DB::raw('date(created_at)'), '=', $curr_date)->groupBy('stationid')->get();


        return view('dashboard.index', ['stations_cnt' => $stations_cnt, 'pumps_cnt' => $pumps_cnt, 'attendants_cnt' => $attendants_cnt, 'total_sales' => $total_sales, 'station_sales' => $station_sales]);
    }

    public function getstarted()
    {
        return view('dashboard.getstarted');
    }

}

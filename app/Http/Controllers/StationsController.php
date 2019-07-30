<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
use App\User;
use App\Pump;
use App\Eoday;
use Validator;
use PDF;
use Auth;

class StationsController extends Controller
{

    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->orderBy('created_at','asc')->paginate(7);
        return View('stations.index',['stations'=> $stations]);
    }

    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'station' => 'required|unique:stations',
            'die_open_stock' => 'required',
            'pet_open_stock' => 'required'
        ]);
        
        
        $station = new Station;
        $station->station = $request->input('station');
        $station->companyid = $companyid;
        $station->save();

        $stationid = $station->id;

        $eoday = new Eoday;
        $eoday->stationid = $stationid;
        $eoday->companyid = $companyid;
        $eoday->fueltype = "Diesel";
        $eoday->tot_vol = 0;
        $eoday->rate = 0;
        $eoday->tot_val = 0;
        $eoday->tot_coll = 0;
        $eoday->shortage = 0;
        $eoday->open_stock = 0;
        $eoday->purchases = 0;
        $eoday->close_stock = $request->input('die_open_stock');
        $eoday->banked = 0;
        $eoday->mpesa = 0;
        $eoday->credit = 0;
        $eoday->expenses = 0;
        $eoday->pos_cash = 0;
        $eoday->pos_mpesa = 0;
        $eoday->pos_credit = 0;
        $eoday->pos_total = 0;
        $eoday->save();

        $eoday = new Eoday;
        $eoday->stationid = $stationid;
        $eoday->companyid = $companyid;
        $eoday->fueltype = "Petrol";
        $eoday->tot_vol = 0;
        $eoday->rate = 0;
        $eoday->tot_val = 0;
        $eoday->tot_coll = 0;
        $eoday->shortage = 0;
        $eoday->open_stock = 0;
        $eoday->purchases = 0;
        $eoday->close_stock = $request->input('pet_open_stock');
        $eoday->banked = 0;
        $eoday->mpesa = 0;
        $eoday->credit = 0;
        $eoday->expenses = 0;
        $eoday->pos_cash = 0;
        $eoday->pos_mpesa = 0;
        $eoday->pos_credit = 0;
        $eoday->pos_total = 0;
        $eoday->save();
        
        return redirect('/stations')->with('success', 'Station Created');

    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $station = Station::where('companyid', '=', $companyid)->find($id);
        $pumps = Pump::where('companyid', '=', $companyid)->where('stationid', '=', $id)->get();
        $attendants = User::where('companyid', '=', $companyid)->where('stationid','=',$id)->where('usertype','=','attendant')->get();
        return view('stations.show', ['station' => $station, 'pumps' => $pumps, 'attendants' => $attendants]);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $station = Station::where('companyid', '=', $companyid)->find($id);
        return view('stations.edit')->with('station', $station);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'station' => 'required|unique:stations'
        ]);
        
        
        $station = Station::find($id);
        $station->companyid = $companyid;
        $station->station = $request->input('station');
        $station->save();

        return redirect('/stations')->with('success', 'Station Updated');
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $station = Station::where('companyid', '=', $companyid)->find($id);
        $station->delete();
        return redirect('/stations')->with('success', 'Station Removed');
    }
}

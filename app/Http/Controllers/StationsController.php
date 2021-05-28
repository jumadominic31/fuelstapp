<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
use App\User;
use App\Pump;
use App\Town;
use App\Eoday;
use Validator;
use PDF;
use Auth;

class StationsController extends Controller
{

    public function index()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->orderBy('station','asc')->paginate(10);
        return View('stations.index',['stations'=> $stations]);
    }

    public function create()
    {
        $towns = Town::select('name', 'id')->pluck('name', 'id')->toArray();
        return view('stations.create', ['towns' => $towns]);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'station' => 'required|unique:stations',
            'townid' => 'required',
            'status' => 'required'
        ]);
        
        $station = new Station;
        $station->station = $request->input('station');
        $station->town_id = $request->input('townid');
        $station->status = $request->input('status');
        $station->companyid = $companyid;
        $station->save();

        return redirect('/stations')->with('success', 'Station Created');
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $station = Station::where('companyid', '=', $companyid)->find($id);
        $pumps = Pump::where('companyid', '=', $companyid)->where('stationid', '=', $id)->get();
        // $attendants = User::where('companyid', '=', $companyid)->where('stationid','=',$id)->where('usertype','=','attendant')->get();
        return view('stations.show', ['station' => $station, 'pumps' => $pumps]);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $towns = Town::select('name', 'id')->pluck('name', 'id')->toArray();
        $station = Station::where('companyid', '=', $companyid)->find($id);

        return view('stations.edit', ['station' => $station, 'towns' => $towns]);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'station' => 'required|unique:stations,station,'.$id,
            'townid' => 'required'
        ]);
        
        
        $station = Station::find($id);
        $station->companyid = $companyid;
        $station->station = $request->input('station');
        $station->town_id = $request->input('townid');
        $station->status = $request->input('status');
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

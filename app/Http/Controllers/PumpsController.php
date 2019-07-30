<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pump;
use App\Station;
use App\User;
use App\Reading;
use Auth;

class PumpsController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $pumps = Pump::where('companyid', '=', $companyid)->orderBy('created_at','asc')->paginate(10);
        return View('pumps.index')->with('pumps', $pumps);
    }

    public function fuelattendantpumps($fueltype, $attendantid){
        $companyid = Auth::user()->companyid;
        $stationid = User::where('companyid', '=', $companyid)->where('id','=',$attendantid)->pluck('stationid');
        $pump = Pump::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->where('fueltype', '=', $fueltype)->pluck('pumpname','id');
        return response()->json($pump);
    }

    public function attendantpumps($attendantid){
        $companyid = Auth::user()->companyid;
        $stationid = User::where('companyid', '=', $companyid)->where('id','=',$attendantid)->pluck('stationid');
        $pump = Pump::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->pluck('pumpname','id');
        return response()->json($pump);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        $users = User::where('companyid', '=', $companyid)->where('usertype','=','attendant')->pluck('username', 'id')->all();
        return view('pumps.create', ['stations' => $stations, 'users' => $users]);
    }

    public function getattendants($stationid)
    {
        $companyid = Auth::user()->companyid;
        $attendants = User::select('id','username')->where('companyid', '=', $companyid)->where('stationid','=', $stationid)->where('usertype','=','attendant')->get();
        return response()->json($attendants);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'pumpname' => 'required|unique:pumps',
            'fueltype' => 'required',
            'pumpreading' => 'required|numeric',
            'stationid' => 'required',
            'attendantid' => 'required'
        ]);
        
        $stationid = $request->input('stationid');
        $pump = new Pump;
        $pump->companyid = $companyid;
        $pump->pumpname = $request->input('pumpname');
        $pump->fueltype = $request->input('fueltype');
        $pump->stationid = $stationid;
        $pump->attendantid = $request->input('attendantid');
        $pump->save();

        //$pumpid = Station::orderBy('id','desc')->pluck('id')->first();
        $pumpid = $pump->id;
        $userid = Auth::user()->id;
			
        $pumpreading = new Reading;
        $pumpreading->companyid = $companyid;
        $pumpreading->pumpid    = $pumpid;
        $pumpreading->stationid = $stationid;
        $pumpreading->previous  = 0;
        $pumpreading->current   = $request->input('pumpreading');
        $pumpreading->diff      = 0;
        $pumpreading->updated_by= $userid; //get userid
        $pumpreading->eoday_id  = 0;
        $pumpreading->save();
        
        //return response()->json($pump);
        return redirect('/pumps')->with('success', 'Pump Created');
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $pump = Pump::where('companyid', '=', $companyid)->find($id);
        return response()->json($pump);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $pump = Pump::find($id);
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id');
        $users = User::where('companyid', '=', $companyid)->where('usertype','=','attendant')->pluck('username', 'id');
        return view('pumps.edit', ['pump'=> $pump, 'stations' => $stations, 'users' => $users]);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'fueltype' => 'required',
            'stationid' => 'required',
            'attendantid' => 'required'
        ]);
        
        
        $pump = Pump::find($id);
        //$pump->pumpname = $request->input('pumpname');
        $pump->companyid = $companyid;
        $pump->fueltype = $request->input('fueltype');
        $pump->stationid = $request->input('stationid');
        $pump->attendantid = $request->input('attendantid');
        $pump->save();

        return redirect('/pumps')->with('success', 'Pump Details Updated');
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $pump = Pump::where('companyid', '=', $companyid)->find($id);
        $pump->delete();
        return redirect('/pumps')->with('success', 'Pump Removed');
    }
}

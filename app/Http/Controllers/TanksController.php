<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tank;
use App\Station;
use App\User;
use App\Reading;
use Auth;

class TanksController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $tanks = Tank::where('companyid', '=', $companyid)->orderBy('created_at','asc')->paginate(10);
        return View('tanks.index')->with('tanks', $tanks);
    }

    public function fuelattendanttanks($attendantid){
        $companyid = Auth::user()->companyid;
        $stationid = User::where('companyid', '=', $companyid)->where('id','=',$attendantid)->pluck('stationid');
        $tank = Tank::select('id', 'tankname', 'fueltype')->where('companyid', '=', $companyid)->where('stationid','=',$stationid)->get();
        return response()->json($tank);
    }

    public function attendanttanks($attendantid){
        $companyid = Auth::user()->companyid;
        $stationid = User::where('companyid', '=', $companyid)->where('id','=',$attendantid)->pluck('stationid');
        $tank = Tank::where('companyid', '=', $companyid)->where('stationid','=',$stationid)->pluck('tankname','id');
        return response()->json($tank);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        return view('tanks.create', ['stations' => $stations]);
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
            'tankname' => 'required|unique:tanks',
            'fueltype' => 'required',
            'stationid' => 'required'
        ]);
        
        $stationid = $request->input('stationid');
        $tank = new Tank;
        $tank->companyid = $companyid;
        $tank->tankname = $request->input('tankname');
        $tank->fueltype = $request->input('fueltype');
        $tank->stationid = $stationid;
        $tank->save();

        //$tankid = Station::orderBy('id','desc')->pluck('id')->first();
        $tankid = $tank->id;
        $userid = Auth::user()->id;
			
        // $tankreading = new Reading;
        // $tankreading->companyid = $companyid;
        // $tankreading->tankid    = $tankid;
        // $tankreading->stationid = $stationid;
        // $tankreading->previous  = 0;
        // $tankreading->current   = $request->input('tankreading');
        // $tankreading->diff      = 0;
        // $tankreading->updated_by= $userid; //get userid
        // $tankreading->eoday_id  = 0;
        // $tankreading->save();
        
        //return response()->json($tank);
        return redirect('/tanks')->with('success', 'Tank Created');
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $tank = Tank::where('companyid', '=', $companyid)->find($id);
        return response()->json($tank);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $tank = Tank::find($id);
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->toArray();
        return view('tanks.edit', ['tank'=> $tank, 'stations' => $stations]);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'fueltype' => 'required',
            'stationid' => 'required'
        ]);
        
        
        $tank = Tank::find($id);
        //$tank->tankname = $request->input('tankname');
        $tank->companyid = $companyid;
        $tank->fueltype = $request->input('fueltype');
        $tank->stationid = $request->input('stationid');
        $tank->save();

        return redirect('/tanks')->with('success', 'Tank Details Updated');
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $tank = Tank::where('companyid', '=', $companyid)->find($id);
        $tank->delete();
        return redirect('/tanks')->with('success', 'Tank Removed');
    }
}

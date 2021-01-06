<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Vehicle;
use App\Owner;
use Validator;
use PDF;
use Auth;

class VehiclesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        
        $owners = Owner::where('companyid', '=', $companyid)->orderBy('fullname')->pluck('fullname','id')->all();

        $vehicles = Vehicle::where('companyid', '=', $companyid)->orderBy('id','asc');
	    $count = Vehicle::where('companyid', '=', $companyid);
	    
	    $num_plate = $request->input('num_plate');
	    $owner_id = $request->input('owner_id');

        if ($num_plate != NULL){
            $vehicles= $vehicles->where('num_plate','like','%'.$num_plate.'%');
            $count = $count->where('num_plate','like','%'.$num_plate.'%');
        }
        if ($owner_id != NULL){
            $vehicles= $vehicles->where('owner_id','=', $owner_id);
            $count = $count->where('owner_id','=', $owner_id);
        }

        $vehicles = $vehicles->paginate(10);
        $count = $count->count();

        return View('vehicles.index', ['owners' => $owners ,'vehicles'=> $vehicles, 'count' => $count]);
    }

    public function create()
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $owners = Owner::where('companyid', '=', $companyid)->orderBy('fullname')->pluck('fullname','id')->all();
        return view('vehicles.create', ['owners' => $owners]);
    }

    public function store(Request $request)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $this->validate($request, [
            // 'num_plate' => array('required', 'regex:/^\S+$/', 'unique:vehicles'),
            'num_plate' => 'required|unique:vehicles',
            'owner_id' => 'required'
        ]);
        
        $vehicle = new Vehicle;
        $num_plate = str_replace(' ', '', strtoupper($request->input('num_plate')));
        $vehicle->num_plate = $num_plate;
        $vehicle->owner_id = $request->input('owner_id');
        $vehicle->category = $request->input('category');
        $vehicle->make = $request->input('make');
        $vehicle->colour = $request->input('colour');
        $vehicle->companyid = $companyid;
        $vehicle->save();

        DB::statement("UPDATE txns INNER JOIN vehicles ON vehicles.num_plate = txns.vehregno SET txns.ownerid = vehicles.owner_id WHERE txns.vehregno = '$num_plate'");

        return redirect('/vehicles')->with('success', 'Vehicle Added');

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $owners = Owner::where('companyid', '=', $companyid)->orderBy('fullname')->pluck('fullname','id')->all();
        $vehicle = Vehicle::where('companyid', '=', $companyid)->find($id);
        if ($vehicle ==  NULL)
        {
        	return redirect('/vehicles')->with('error', 'Invalid Vehicle');
        }
        return view('vehicles.edit', ['vehicle' => $vehicle, 'owners' => $owners]);
    }

    public function update(Request $request, $id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $this->validate($request, [
        	'num_plate' => 'required|unique:vehicles,num_plate,'.$id,
            'owner_id' => 'required'
        ]);
        
        $num_plate = str_replace(' ', '', strtoupper($request->input('num_plate')));
        $vehicle = Vehicle::find($id);
        $vehicle->num_plate = $num_plate;
        $vehicle->owner_id = $request->input('owner_id');
        $vehicle->category = $request->input('category');
        $vehicle->make = $request->input('make');
        $vehicle->colour = $request->input('colour');
        $vehicle->save();

        DB::statement("UPDATE txns INNER JOIN vehicles ON vehicles.num_plate = txns.vehregno SET txns.ownerid = vehicles.owner_id WHERE txns.vehregno = '$num_plate'");

        return redirect('/vehicles')->with('success', 'Vehicle Details Updated');
    }

    public function destroy($id)
    {

    }

    public function getvehicles(){
        $companyid = Auth::user()->companyid;
        $vehicles = Vehicle::select('num_plate')->where('companyid', '=', $companyid)->pluck('num_plate');
        return response()->json($vehicles);
    }
}

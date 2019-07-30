<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Owner;
use Validator;
use PDF;
use Auth;

class OwnersController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyid = $user->companyid;
        
        $owners = Owner::where('companyid', '=', $companyid)->orderBy('own_num','asc');
	    $count = Owner::where('companyid', '=', $companyid);

	    $this->validate($request, [
            'own_num' => 'nullable|numeric'
        ]);
	    
	    $own_num = $request->input('own_num');
	    $fullname = $request->input('fullname');

        if ($own_num != NULL){
            $owners= $owners->where('own_num','like','%'.$own_num.'%');
            $count = $count->where('own_num','like','%'.$own_num.'%');
        }
        if ($fullname != NULL){
            $owners= $owners->where('fullname','like','%'.$fullname.'%');
            $count = $count->where('fullname','like','%'.$fullname.'%');
        }

        $owners = $owners->paginate(10);
        $count = $count->count();

        return View('owners.index',['owners'=> $owners, 'count' => $count]);
    }

    public function create()
    {
        return view('owners.create');
    }

    public function store(Request $request)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $this->validate($request, [
            'own_num' => 'required|numeric|unique:owners',
            'fullname' => 'required',
            'phone' => array('nullable', 'regex:/^[0-9]{12}$/')
        ]);
        
        $owner = new Owner;
        $owner->own_num = $request->input('own_num');
        $owner->fullname = ucwords(strtolower($request->input('fullname')));
        $owner->phone = $request->input('phone');
        $owner->companyid = $companyid;
        $owner->save();

        return redirect('/owners')->with('success', 'Owner Added');

    }

    public function show($id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $owner = Owner::where('companyid', '=', $companyid)->find($id);
        $pumps = Pump::where('companyid', '=', $companyid)->where('ownerid', '=', $id)->get();
        $attendants = User::where('companyid', '=', $companyid)->where('ownerid','=',$id)->where('usertype','=','attendant')->get();
        return view('owners.show', ['owner' => $owner, 'pumps' => $pumps, 'attendants' => $attendants]);
    }

    public function edit($id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $owner = Owner::where('companyid', '=', $companyid)->find($id);
        if ($owner ==  NULL)
        {
        	return redirect('/owners')->with('error', 'Invalid Owner');
        }
        return view('owners.edit')->with('owner', $owner);
    }

    public function update(Request $request, $id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $this->validate($request, [
        	'own_num' => 'required|numeric|unique:owners,own_num,'.$id,
            'fullname' => 'required',
            'phone' => array('nullable', 'regex:/^[0-9]{12}$/')
        ]);
        
        $owner = Owner::find($id);
        $owner->own_num = $request->input('own_num');
        $owner->fullname = ucwords(strtolower($request->input('fullname')));
        $owner->phone = $request->input('phone');
        $owner->save();

        return redirect('/owners')->with('success', 'Owner Details Updated');
    }

    public function destroy($id)
    {
    	$user = Auth::user();
        $companyid = $user->companyid;
        $owner = Owner::where('companyid', '=', $companyid)->find($id);
        $owner->delete();
        return redirect('/owners')->with('success', 'Owner Removed');
    }
}

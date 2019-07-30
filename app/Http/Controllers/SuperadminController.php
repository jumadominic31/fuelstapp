<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Station;
use App\Pump;
use App\User;
use Auth;

class SuperadminController extends Controller
{
    public function index()
    {
        $companies_cnt = Company::count();
        $companies = Company::all();
        $stations_cnt = Station::count();
        $pumps_cnt = Pump::count();
        $users_cnt = User::where('usertype','!=','superadmin')->count();
        return view('superadmin.index', ['companies_cnt' => $companies_cnt, 'companies' => $companies, 'stations_cnt' => $stations_cnt, 'pumps_cnt' => $pumps_cnt, 'users_cnt' => $users_cnt]);
    }

    public function companyindex()
    {
        $companies = Company::all();
        return view('superadmin.company.index', ['companies' => $companies]);
    }

    public function companycreate()
    {
        return view('superadmin.company.create');
    }

    public function companystore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:companies',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'email',
            'logo' => 'image|max:1999',
            'status' => 'required',
            'username' => 'required',
            'fullname' => 'required',
            'adminphone' => 'required',
            'password' => 'required',
            'adminstatus' => 'required'
        ]);
        if ($request->file('logo') != NULL){ 
            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // Uplaod image
            $path= $request->file('logo')->storeAs('public/company_logos', $filenameToStore);
        }
        $company = new Company;
        $company->name = $request->input('name');
        $company->address = $request->input('address');
        $company->city = $request->input('city');
        $company->phone = $request->input('phone');
        $company->email = $request->input('email');
        $company->status = $request->input('status');
        if ($request->file('logo') != NULL){ 
            $company->logo = $filenameToStore;
        }
        $company->save();

        $compadmin = new User;
        $compadmin->username = $request->input('username');
        $compadmin->fullname = $request->input('fullname');
        $compadmin->phone = $request->input('adminphone');
        $compadmin->password = bcrypt($request->input('password'));
        $compadmin->companyid = $company->id;
        $compadmin->status = $request->input('adminstatus');
        $compadmin->usertype = 'admin';
        $compadmin->save();

        return redirect('/superadmin/company')->with('success', 'Company Created');
    }

    public function companyedit($id)
    {
        $company = Company::find($id);
        return view('superadmin.company.edit',['company'=> $company]);
    }

    public function companyupdate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'email',
            'logo' => 'image|max:1999',
            'status' => 'required'
        ]);
        
        if ($request->file('logo') != NULL){ 
            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // Uplaod image
            $path= $request->file('logo')->storeAs('public/company_logos', $filenameToStore);
        }
        $company = Company::find($id);
        $company->name = $request->input('name');
        $company->address = $request->input('address');
        $company->city = $request->input('city');
        $company->phone = $request->input('phone');
        $company->email = $request->input('email');
        $company->status = $request->input('status');
        if ($request->file('logo') != NULL){
            $company->logo = $filenameToStore;
        }
        $company->save();
        
        return redirect('/superadmin/company')->with('success', 'Company details updated');
    }

    public function companydestroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return redirect('/superadmin/company')->with('success', 'Company Deleted');
    }
}

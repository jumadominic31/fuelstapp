<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPassword;
use App\User;
use Validator;
use Auth;
use Session;
use App\Station;
use App\User_login;
use App\Vehicle;
use App\Pump;
use App\Rate;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UsersController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $users = User::where('companyid', '=', $companyid)->orderBy('created_at','asc')->paginate(10);
        //return response()->json($stations);
        return View('users.index')->with('users', $users);
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'fullname' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'stationid' => 'required',
            'status' => 'required',
            'usertype' => 'required'            
        ]);
        $user = new User([
            'username' => $request->input('username'),
            'fullname' => $request->input('fullname'),
            'companyid' => $companyid,
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
            'stationid' => $request->input('stationid'),
            'status' => $request->input('status'),
            'usertype' => $request->input('usertype')
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function signin(Request $request)
    {
          $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $rate_date = date('Y-m-d');
        $credentials = $request->only('username', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials!',
                    'status' => 'failed'
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!',
                'status' => 'failed'
            ], 200);
        }

        $username = $request->input('username');
        $userdetails = DB::table('users')
            ->join('stations', 'users.stationid', '=', 'stations.id')
            ->join('companies', 'users.companyid', '=', 'companies.id')
            ->select('users.id', 'users.username', 'users.stationid', 'stations.station', 'stations.receipt_header', 'users.companyid', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email' )
            ->where('users.username', '=', $username)
            ->get();
        $companyid = $userdetails[0]->companyid;
        $vehicles = Vehicle::select('num_plate')->where('companyid', '=', $companyid)->pluck('num_plate');
        $rates =  Rate::where('companyid', '=', $companyid)->where('start_rate_date', '<=', $rate_date)->where('end_rate_date','>=',$rate_date)->get();            
        $stations = Station::where('companyid', '=', $companyid)->where('status', '=', '1')->get();
        $pumps = Pump::where('companyid', '=', $companyid)->get();
        
        //to log user signin to user_logins table
        $userlogin = new User_login();
        $userlogin->username = $request->input('username');
        $userlogin->activity = "Login";
        $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlogin->save();
        //
        return response()->json([
            'token' => $token,
            'userdetails' => $userdetails,
            'vehicles' => $vehicles,
            'rates' => $rates,
            'station' => $stations,
            'pumps' => $pumps,
            'status' => 'success'
        ], 200);
    }

    public function getuserdetails($username)
    {
        $userdetails = DB::table('users')
            ->join('stations', 'users.stationid', '=', 'stations.id')
            ->join('companies', 'users.companyid', '=', 'companies.id')
            ->select('users.id', 'users.username', 'users.stationid', 'stations.station', 'stations.receipt_header', 'users.companyid', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email' )
            ->where('users.username', '=', $username)
            ->get();
        return response()->json($userdetails);
    }

     public function getSignin()
    {
        return view('users.signin');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials1 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'admin');
        $credentials2 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'stationadmin');
        $credentials3 = array('username' => $request->input('username'), 'password' => $request->input('password'), 'usertype' => 'superadmin');

        if ((Auth::attempt($credentials1)) OR (Auth::attempt($credentials2))) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //to log user signin to user_logins table
            $userlogin = new User_login();
            $userlogin->username = $request->input('username');
            $userlogin->activity = "Login";
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            //Userdetails
            $userdetails = User::join('companies', 'users.companyid', '=', 'companies.id')->select('users.id', 'users.username', 'users.stationid','users.companyid', 'companies.name', 'companies.address', 'companies.city', 'companies.phone', 'companies.email', 'companies.logo' )->where('users.username', '=', $userlogin->username)->get();

            $companyname = $userdetails[0]->name;
            $companylogo = $userdetails[0]->logo;

            session(['fuelstapp.companyname' => $companyname]);
            session(['fuelstapp.companylogo' => $companylogo]);

            return redirect()->route('dashboard.index', ['companyname' => $companyname, 'companylogo' => $companylogo]);
        }
        else if (Auth::attempt($credentials3)) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //to log user signin to user_logins table
            $userlogin = new User_login();
            $userlogin->username = $request->input('username');
            $userlogin->activity = "Login";
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            return redirect()->route('superadmin.index');
        }
        return redirect()->back()->with('error', 'Incorrect username/password');
    }

    public function getLogout() {
        Auth::logout();
        return redirect()->route('users.signin');
    }

    public function getProfile() {
        $user = Auth::user();
        return view('users.profile', ['user' => $user]);
    }

    public function changePassword(Request $request) {
        $validator = Validator::make(($request->all()), [
            'curr_password' => 'required',
            'new_password' => 'required|same:new_password',
            'new_password_2' => 'required|same:new_password'
        ]);

        if ($validator->fails()){
            //$response = array('response' => $validator->messages(), 'success' => false);
            $response = array('message' => 'The 2 new passwords do not match');
            return $response;
        } else {

            $current_password = Auth::User()->password;

            if(Hash::check($request->input('curr_password'), $current_password)){
                $request->user()->fill([
                    'password' => Hash::make($request->input('new_password'))
                ])->save();
                return response()->json(['message' => 'Password changed', 'status' => 'success'], 200);
            } else {
                return response()->json(['message' => 'Current password incorrect', 'status' => 'failed'], 200);
            }
        }
    }

    public function resetpass(){
        return view('users.resetpass');
    }

    public function postResetpass(Request $request) {
        $this->validate($request, [
            'curr_password' => 'required',
            'new_password_1' => 'required|same:new_password_1',
            'new_password_2' => 'required|same:new_password_1'
        ]);

        $current_password = Auth::User()->password;

        if(Hash::check($request->input('curr_password'), $current_password)){
            $request->user()->fill([
                'password' => Hash::make($request->input('new_password_1'))
            ])->save();

            //to log user password reset to user_logins table
            $username = Auth::user()->username;
            $userlogin = new User_login();
            $userlogin->username = $username;
            $userlogin->activity = "Password reset for ".$username;
            $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
            $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
            $userlogin->save();

            return redirect('/users/profile')->with('success', 'Password Changed');
        } else {
            return redirect('/users/resetpass')->with('error', 'Current password incorrect');
        }

    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        return view('users.create', ['stations' => $stations, 'companyid' => $companyid]);
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'username' => 'required|unique:users',
            'fullname' => 'required',
            'phone' => array('required', 'regex:/^[0-9]{12}$/'),
            'email' => 'sometimes|nullable|email',
            'pass1' => 'required|same:pass1',
            'pass2' => 'required|same:pass1',
            'stationid' => 'required',
            'status' => 'required',
            'usertype' => 'required' 
        ]);

        //Set new random password
        function randomPassword() {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
        }
        
        // $password = randomPassword();
        $password = $request->input('pass1');
        $email = $request->input('email');

        $user = new User;
        $user->username = $request->input('username');
        $user->fullname = $request->input('fullname');
        $user->companyid = $companyid;
        $user->phone = $request->input('phone');
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->stationid = $request->input('stationid');
        $user->status = $request->input('status');
        $user->usertype = $request->input('usertype');
        $user->save();

        //Mail::to($email)->send(new SendPassword($password)); 

        return redirect('/users')->with('success', 'User Created');
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $stations = Station::where('companyid', '=', $companyid)->pluck('station','id')->all();
        $user = User::where('companyid', '=', $companyid)->find($id);
        if ($user == null){
            return redirect('/users')->with('error', 'User not found');
        }
        return view('users.edit',['user'=> $user, 'stations' => $stations]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'pass1' => 'same:pass1',
            'pass2' => 'same:pass1',
            'phone' => 'required',
            'stationid' => 'required',
            'status' => 'required',
            'usertype' => 'required' 
        ]);
        $password = $request->input('pass1');
        
        $user = User::find($id);
        $user->fullname = $request->input('fullname');
        if ($password != NULL)
        {
            $user->password = bcrypt($password);
        }
        $user->phone = $request->input('phone');
        $user->stationid = $request->input('stationid');
        $user->status = $request->input('status');
        $user->usertype = $request->input('usertype');
        $user->save();
        
        return redirect('/users')->with('success', 'User details updated');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/users')->with('success', 'User Removed');
    }

    public function resetPassword($id) {
        $user = User::find($id);
        $user->password = bcrypt('nucleur123');
        $user->save();

        //to log user password reset to user_logins table
        $userlogin = new User_login();
        $userlogin->username = Auth::user()->username;
        $userlogin->activity = "Password reset for ".$user->username;
        $userlogin->ipaddress = $_SERVER['REMOTE_ADDR'];
        $userlogin->useragent = $_SERVER['HTTP_USER_AGENT'];
        $userlogin->save();

        return redirect('/users')->with('success', 'Password Reset');
    }
}
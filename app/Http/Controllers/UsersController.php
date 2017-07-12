<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $credentials = $request->only('username', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials!'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!'
            ], 500);
        }
        
        return response()->json([
            'token' => $token
        ], 200);
    }

    public function getuserdetails($username)
    {
        $userdetails = DB::table('users')
            ->join('stations', 'users.stationid', '=', 'stations.id')
            ->select('users.id', 'users.username', 'users.stationid', 'stations.station' )
            ->where('users.username', '=', $username)
            ->get();
        return response()->json($userdetails);
    }
}
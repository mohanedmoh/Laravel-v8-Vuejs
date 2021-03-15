<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\User_type;

class Users extends Controller
{
    public $successStatus = 200;
    private $row = [];
    private $result = null;
    private $error = 0;
    private $code = null;
    public function records()
    {
        $users = [];
        $user_types = [];
        try {
            $users = User::active()->with("user_type")->with("RolePermission")->orderBy('id', 'desc')->get();
            $user_types = User_type::active()->select('id as value', 'title as text')->get();

            $this->error = 1;
            $this->result = "Success";
            $this->code = 1;
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->error = 0;
            $this->result = $ex->errorInfo['2'];
            $this->code = $ex->getCode();
        }
        $data = [
            "error" => $this->error,
            "code" =>  $this->code,
            "result" => $this->result,
            "data" => ["users" => $users, "user_types" => $user_types]
        ];
        return response()->json($data)->getContent();
    }
    public function signin(Request $request)
    {
        $token = '';
        $user = [];

        $validation = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            $this->error = 0;
            $this->result = "Validation Error";
        } else {
            try {

                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials, true)) {



                    $token =  Auth::user()->createToken('Tap')->plainTextToken;
                    $user = Auth::user();
                    $user->token = $token;

                    $this->error = 1;
                    $this->code = 1;
                    $this->result = "Success";
                } else {

                    $this->error = 0;
                    $this->code = 2;
                    $this->result = "Wrong email or password, please try again";
                    $this->successStatus = 401;
                }
            } catch (\Illuminate\Database\QueryException $ex) {

                $this->error = 0;
                $this->result = $ex->errorInfo[2];
                $this->code = $ex->getCode();
            }
        }
        $data = [
            "error" => $this->error,
            "code" =>  $this->code,
            "result" => $this->result,
            "data" => ['token' => $token, 'user' => $user]
        ];
        return response()->json($data, $this->successStatus)->getContent();
    }
    public function getUser()
    {
        $user = User::active()->with("user_type")->with("RolePermission")->orderBy('id', 'desc')->get();
        return response()->json(['data' => $user], $this->successStatus);
    }
    public function getUserById(Request $request,$id)
    {
        $user = User::active()->with("user_type")->with("RolePermission")->where("id",$id)->orderBy('id', 'desc')->get();
        return response()->json(['data' => $user], $this->successStatus);
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, true)) {
            return redirect()->route('Home');
        } else return redirect()->back()->with('error', 'Email or password incorrect, Please try again');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('error', 'You Logged Out Successfully');
    }
    public function register(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            $this->error = 0;
            $this->result = "Validation Error";
        } else {
            try {
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);

                $user = User::create($input);

                if ($user) {
                    $this->row = $user;
                    $this->error = 1;
                    $this->result = "Success";
                    $this->code = 1;
                } else {
                    $this->error = 0;
                    $this->result = "fail";
                    $this->code = 2;
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $this->error = 0;
                $this->result = $ex->errorInfo[2];
                $this->code = $ex->getCode();
            }
        }
        $data = [
            "error" => $this->error,
            "code" =>  $this->code,
            "result" => $this->result,
            "data" => $this->row
        ];
        return response()->json($data, $this->successStatus)->getContent();
    }
    public function set(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [

            'type_id' => 'required|numeric',
            'id' => 'nullable|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',

        ]);

        if ($validation->fails()) {
            //  $this->row=$request->all();
            $this->error = 0;
            $this->result = "Validation Error";
        } else {

            try {
                $obj = User::find($id);

                if (isset($request['password'])) {
                    $obj->password = bcrypt($request->password);
                }
                $obj->type_id = $request->type_id;
                $obj->name = $request->name;
                $obj->email = $request->email;

                if ($obj->save()) {
                    $this->error = 1;
                    $this->result = "Success";
                    $this->code = 1;
                } else {
                    $this->error = 0;
                    $this->result = "Some error happened, please try again later";
                    $this->code = 0;
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                $this->error = 0;
                $this->result = $ex->errorInfo['2'];
                $this->code = $ex->getCode();
            }
        }
        $data = [
            "error" => $this->error,
            "code" =>  $this->code,
            "result" => $this->result,
            "data" => $this->row
        ];
        return response()->json($data, 201)->getContent();
    }
    public function remove(Request $request, $id)
    {
        try {
            $obj = User::find($id);
            if ($obj) {
                $obj->status_id = 0;
                if ($obj->save()) {
                    $this->error = 1;
                    $this->result = "Success";
                    $this->code = 1;
                } else {
                    $this->error = 0;
                    $this->result = "Some error happened, please try again later";
                    $this->code = 0;
                }
            } else {
                $this->error = 0;
                $this->result = "no record found";
                $this->code = 0;
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->error = 0;
            $this->result = $ex->errorInfo['2'];
            $this->code = $ex->getCode();
        }

        $data = [
            "error" => $this->error,
            "code" =>  $this->code,
            "result" => $this->result,
            "data" => $this->row
        ];
        return response()->json($data, 201)->getContent();
    }
}

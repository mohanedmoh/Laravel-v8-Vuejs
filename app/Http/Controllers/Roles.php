<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\User_type;
use App\Models\Permission;
use App\Models\RolePermission;

class Roles extends Controller
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
        $permissions = [];
        $data=[];
        try {
            $user_types = User_type::active()->select('id as value', 'title as text')->orderBy('id', 'desc')->get();

            foreach ($user_types as $type) {
                $type->permissions = RolePermission::where('role_id', $type->value)->with('Permission')->select('id','role_id','permission_id')->get();
                // if($data->isEmpty())
                // $type->permissions =null;
                // else $type->permissions=$data;
            }

            $permissions = Permission::select('id as value', 'title as text')->get();
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
            "data" => ["Roles" => $user_types, "Permissions" => $permissions]
        ];
        return response()->json($data)->getContent();
    }
    public function set(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [

            'id' => 'nullable|numeric',
            'name' => 'required|string',
        ]);

        if ($validation->fails()) {
            //  $this->row=$request->all();
            $this->error = 0;
            $this->result = "Validation Error";
        } else {

            try {
                $obj = User::find($id);


                $obj->type_id = $request->type_id;
                $obj->name = $request->name;

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
    public function insert(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'permissions' => 'required',
        ]);

        if ($validation->fails()) {
            $this->error = 0;
            $this->result = "Validation Error";
        } else {
            try {
                $input = $request->all();

                $user = User_type::create($input);
                if ($user) {
                    foreach ($input["permissions"] as $key) {
                        $RP = new RolePermission();
                        $RP["role_id"] = $user["id"];
                        $RP["permission_id"] = $key;
                        $RP->save();
                        //$roleP=RolePermission::create($RP);
                        // printf($key);
                    }
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
    public function remove(Request $request, $id)
    {
        try {
            $obj = User_type::find($id);
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

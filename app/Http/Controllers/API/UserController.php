<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestRegis;
use App\Http\Requests\RequestContact;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function __construct()
{
    $this->user = new User();
}
public function users()
{
    $users= $this->user->users();
    if($users->count()){
        return response()->json([
            'status_code' => 200,
            'message' => 'users fetched successfully',
             'data' => $users   
            ]);
    }
    return response()->json([
        'status_code' => 200,
        'message' => 'users not found',
         'data' => []   
        ]);
    
}
public function login(UserRequest $request)
{
    $validatedData = $request->validated();
    return $this->user->login($validatedData);
}
public function register(UserRequestRegis $request)
{
    $validatedData = $request->validated();
    return $this->user->create_user($validatedData);
    
}
public function getUserById($id)
{
    return $this->user->getUserDetailById($id);
}
public function updateUserById(Request $request,$id)
{
  return $this->user->updateuser($request,$id);
}
public function createContact(RequestContact $request)
{
   
    dd(1);
}
}

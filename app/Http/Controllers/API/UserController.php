<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestRegis;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function login(UserRequest $request)
{
    $validatedData = $request->validated();
    dd($validatedData);
}

public function register(UserRequestRegis $request)
{
    $validatedData = $request->validated();
    $file_name =  str_replace(' ','_',$validatedData['name']).".".$validatedData['profile']->getClientOriginalExtension();
    if( $file_path = $validatedData['profile']->move(public_path().'/images/',$file_name)){
        $userArray = [
            'name'      => $validatedData['name'],
            'email'     => $validatedData['email'],
            'password'  => Hash::make($validatedData['password']),
            'profile' => $file_name
          ];
          $user = User::create($userArray);
          $token = $user->createToken('blogSoaRegis')->accessToken;
          if(User::insert('api_token',$token)->where('id',$user->id)){
              dd("succes");
          }

    }
}

}

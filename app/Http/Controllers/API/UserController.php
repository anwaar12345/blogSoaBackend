<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestRegis;
use App\Http\Requests\RequestContact;
use App\User;
use App\Contact;
use Illuminate\Support\Facades\Hash;
use Auth;
class UserController extends Controller
{
public function __construct()
{
    $this->user = new User();
    $this->contact = new Contact();
}
public function userContacts()
{
    $users= $this->contact->userContacts();
    if($users->count()){
        return response()->json([
            'status_code' => 200,
            'message' => 'Contacts fetched successfully',
             'data' => $users   
            ]);
    }
    return response()->json([
        'status_code' => 200,
        'message' => 'contacts not found',
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
   $validatedData = $request->validated();
   return $this->user->createUserContact($validatedData);    
   
}

public function userLogout(Request $request)
{
  $token = $request->bearerToken('api-token'); 
  $user = User::where('id',Auth::user()->id)->first();
  foreach($user->tokens as $token) {
    $token->revoke();   
}
  if($user != null) {
    $userArray = ['api_token' => null];
    $logout = User::where('id',$user->id)->update($userArray);
    if($logout) {
    Auth::user()->AauthAcessToken()->delete();
      return response()->json([
        'message' => 'User Logged Out',
      ]);
    }
  } else {
    return response()->json([
      'message' => 'User not found',
    ]);
  }
}

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestRegis;
use App\Http\Requests\RequestContact;
use App\Http\Requests\ContactUpdate;
use App\User;
use App\Contact;
use Illuminate\Support\Facades\Hash;
use Auth;
use Helpers;
class UserController extends Controller
{
public function __construct()
{
    $this->user = new User();
    $this->contact = new Contact();
}

public function userContacts(Request $request)
{
    $users= $this->contact->userContacts($request);
    $status_code = 404;
    $status = "Failed";
    $message = "contacts not found";

    if($users->count()){
      $status_code = 200;
      $status = "Success";
      $message = "Contacts fetched successfully";
      $users;
    }

    return Helpers::makeResponse($status_code,$status,$message,$users);
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
public function getContactById($id)
{
    return $this->contact->getContactDetailById($id);
}
public function updateContactById(ContactUpdate $request,$id)
{
  return $this->contact->updateContact($request,$id);
}
public function createContact(RequestContact $request)
{
   $validatedData = $request->validated();
   return $this->user->createUserContact($validatedData);    
   
}

public function userLogout(Request $request)
{

  $status_code = 404;
  $status = "Failed";
  $message = "User not found";

  $token = $request->bearerToken('api-token'); 
  $user = User::where('id',Auth::user()->id)->first();
  
  if($user != null) {

    $userArray = ['api_token' => null];

    if(User::where('id',$user->id)->update($userArray) && Auth::user()->AauthAcessToken()->delete()) {

    foreach($user->tokens as $token) {
       $token->revoke();   
    }

    $status_code = 200;
    $status = "Success";
    $message = "User Logged Out Successfully";
    
    }}
    return Helpers::makeResponse($status_code,$status,$message,[]);
}


public function deleteContactById($id)
{
  return $this->contact->ContactdeleteById($id);
}

}

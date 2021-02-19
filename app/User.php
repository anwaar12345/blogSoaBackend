<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use App\Contact;
use App\OauthAccessToken;
use Auth;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','image','api_token','email','phone','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function create_user($validatedData)
    {
        $file_name =  str_replace(' ','_',$validatedData['first_name']).".".$validatedData['image']->getClientOriginalExtension();
        if( $file_path = $validatedData['image']->move(public_path().'/images/',$file_name)){
            $userArray = [
                'first_name'      => $validatedData['first_name'],
                'last_name'      => $validatedData['last_name'],
                'password'  => Hash::make($validatedData['password']),
                'image' => $file_name,
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
              ];
              $user = User::create($userArray);
              $token = $user->createToken('blogSoaRegis')->accessToken;
              $user->api_token = $token;
              $userArray['api_token'] = $token;
              if($user->save()){
                return response()->json([
                    'status_code' => 200,
                    'status' => 'Success',
                    'message' => 'User Register successfully.',
                    'data' => $userArray
                ]);
              }else{
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User Registeration Failed',
                    'data' => []
                ]);
              }
    
        }
    }
    public function login($validatedData)
    {
        $user = $this->where('email',$validatedData['email'])->first();
        if($user!=null){
            if(password_verify($validatedData['password'],$user->password)){
               $login =  $this->where('email',$validatedData['email'])->update(['api_token' =>  $user->createToken('blogSoaRegis')->accessToken]);
                if($login){
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'User loged in successfully',
                        'data' => $user
                      ]); 
                }
            }else{
                return response()->json([
                    'status_code' => 401,
                    'message' => 'User login failed',
                  ]); 
            }


        }else {
            return response()->json([
              'message' => 'User not found',
            ]);
          }
    }


    public function users()
    {
     return $this->
     select('id','first_name','last_name','email','phone','image')
     ->get();
    }
    public function getUserDetailById($id)
    {
       $user = $this->
       select('id','first_name','last_name','email','phone','image')
       ->where('id',$id)->first();
       if($user != null){
        return response()->json([
            'status_code' => 200,
            'message' => 'User fetched successfully',
            'data' => $user
          ]); 
       }
       return response()->json([
        'status_code' => 404,
        'message' => 'User not found',
        'data' => []
      ]); 

    }
    public function updateuser($request,$id)
    {
        echo "isd:".$id."<br>".$request->first_name;
    }

    public function createUserContact($validatedData)
    {
     $userArray = [
         'first_name'      => $validatedData['first_name'],
         'last_name'      => $validatedData['last_name'],
          'email' => $validatedData['email'],
         'phone' => $validatedData['phone'],
          ]; 
      $userContact =  $this->create($userArray);
      if($userContact){
        if(contact::create(
          [
            'user_id' => Auth::user()->id,
            'contact_id' => $userContact->id
         ]
          )){
            $userArray['user_id'] = Auth::user()->id;
            $userArray['contact_id'] = $userContact->id;
            return response()->json(
              [
               'status_code' => 200,
              'message' => 'Contact created successfully',
              'data' =>    $userArray
              ]
            );
          }
      }
     
    }
    public function AauthAcessToken(){
      return $this->hasMany(OauthAccessToken::class);
  }
}

<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Contact extends Model
{
    protected $fillable = [
        'user_id', 'contact_id',
    ];
    public function userContacts()
    {
        return $this->with(['contacts:id,first_name,last_name,email,phone'])->where('user_id',Auth::user()->id)->get();
    }

    public function contacts()
    {
        return $this->belongsTo(User::class,'contact_id','id');  
    }
    public function getContactDetailById($id)
    {

        $contact = $this->with('contacts:id,first_name,last_name,email,phone,created_at,updated_at')->where('id',$id)->first();
       if($contact != null){
        return response()->json([
            'status_code' => 200,
            'message' => 'Contact fetched successfully',
            'data' => $contact
          ]); 
       }
       return response()->json([
        'status_code' => 404,
        'message' => 'User not found',
        'data' => []
      ]); 

    }
    
    public function updateContact($request,$id)
    {
        $contact_id = $this->where('id',$id)->pluck('contact_id');
        if(!count($contact_id)){
         return "user not found";
        }
        $contact = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone
        ];
       if(User::where('id',$contact_id)->update($contact)){
            return response()->json([
                'status_code' => 200,
                'message' => 'Contact updated successfully',
                'data' => $contact
              ]);
        }else{
            return response()->json([
                'status_code' => 422,
                'message' => 'Contact updation failed',
                'data' => $contact
              ]);
        }
    }
    
}

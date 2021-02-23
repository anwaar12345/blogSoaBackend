<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Helpers;
class Contact extends Model
{
    protected $fillable = [
        'user_id', 'contact_id',
    ];
    public function userContacts($request)
    {
        $user = $this->query();
        if(isset($request->first_name) || isset($request->last_name) || isset($request->email) || isset($request->phone)){
            $user = $user->whereHas('contacts',function($query) use($request){
                $query->where("first_name","LIKE","%".$request->first_name."%");
            });
            $user = $user->whereHas('contacts',function($query) use($request){
                $query->where("last_name","LIKE","%".$request->last_name."%");
            });
            $user = $user->whereHas('contacts',function($query) use($request){
                $query->where("email","LIKE","%".$request->email."%");
            });
            $user = $user->whereHas('contacts',function($query) use($request){
                $query->where("last_name","LIKE","%".$request->phone."%");
            });

            $user = $user->with(['contacts:id,first_name,last_name,email,phone'])->where('user_id',Auth::user()->id)->get();
            return $user;
        }else{
            $user = $user->with(['contacts:id,first_name,last_name,email,phone'])->where('user_id',Auth::user()->id);
        }

        $user = $user->latest()
        ->get();
        return $user;
    }

    public function contacts()
    {
        return $this->belongsTo(User::class,'contact_id','id');  
    }
    public function getContactDetailById($id)
    {
        $status_code = 404;
        $status = "Failed";
        $message = "contact not found";
        $contact = [];
        $contact = $this->with('contacts:id,first_name,last_name,email,phone,created_at,updated_at')->where('id',$id)->first();
       
        if($contact != null){
       
        $status_code = 200;
        $status = "Success";
        $message = "Contact fetched successfully";
        $contact;

       }


     return Helpers::makeResponse($status_code,$status,$message,($contact == NULL) ? [] : $contact);
     
    }
    
    public function updateContact($request,$id)
    {
        $contact_id = $this->where('id',$id)->pluck('contact_id');
        $status_code = 404;
        $status = "Failed";
        $message = "contact not found";
        $contact = [];

        if(count($contact_id)){
              $contact = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone
            ];
            
           if(User::where('id',$contact_id)->update($contact)){

            $status_code = 200;
            $status = "Success";
            $message = "Contact fetched successfully";
            $contact;
            
            }
           
        }
        
    
    return Helpers::makeResponse($status_code,$status,$message,$contact);
    
}
    public function ContactdeleteById($id)
    {
        $user = $this->find($id);
        $status_code = 404;
        $status = "Failed";
        $message = "contact not found";
        $contact = [];
        if($user != NULL){
            $contact = User::where('id',$user->contact_id)->get();
            $user->delete();
            if(User::where('id',$user->contact_id)->delete()){
                $status_code = 200;
                $status = "Success";
                $message = "Contact deleted successfully";
                $contact;
            }
        }
        
        return Helpers::makeResponse($status_code,$status,$message,($contact != NULL ) ? $contact[0] : []);

    }
}

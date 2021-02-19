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
}

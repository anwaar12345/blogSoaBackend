<?php 
namespace App\Helpers;

class Helpers{
    public static function makeResponse($status_code = '', $status = '',$message = '', $data = '')
    {
        return response()->json([
            'status_code' => $status_code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
 }

?>
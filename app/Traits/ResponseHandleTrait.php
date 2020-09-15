<?php

namespace App\Traits;

trait ResponseHandleTrait {

    public function HandleResponse($status,$message,$status_code){
         return response()->json([
             "status"=> $status,
             "message" => $message,
             "status_code" => $status_code
         ],$status_code);
    }

    public function HandleResponseWithData($data,$status,$message,$status_code){
        return response()->json([
             "book" => $data,
             "status"=> $status,
             "message" => $message,
             "status_code" => $status_code
        ],$status_code);
    }

}

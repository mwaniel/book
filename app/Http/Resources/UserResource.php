<?php

namespace App\Http\Resources;

use Facade\FlareClient\Http\Response;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response as FacadesResponse;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "user" =>[
            "name" => $this->name,
            "email" => $this->email,
            "id" => $this->id,
            ],
            "status" =>true,
            "message"=> "User Registered Successfully",
            "status_code" => 201
        ];
    }
}

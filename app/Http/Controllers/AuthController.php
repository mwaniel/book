<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Role;
use App\Traits\ResponseHandleTrait;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    //
     use ResponseHandleTrait;

      public function register(Request $request ){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        try{
        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if($user){
            Role::create(["name"=> "Admin", "user_id" => $user->id]);
            return response()->json(new UserResource($user),201);
            }else {
                return $this->HandleResponse(false,"Something went wrong",400);
            }
        }catch(Exception $e){
            return $this->HandleResponse(false,$e->getMessage(),400);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'remember_me' => 'boolean',
        ]);
        $email = $request->email;
        $password = $request->password;
        $remember_me = $request->remember_me;
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return $this->HandleResponse(false,"Wrong Credential, Try Again",201);
        }else {
            $user= $request->user();
            $roles = $user->roles;
            if($roles->count() > 0){
                foreach($roles as $role){
                    if($role->name === "Admin"){
                        $tokenData = $user->createToken('Personal Access Token', ['do_anything']);
                    }else{
                        $tokenData = $user->createToken('Personal Access Token', ['can_create']);
                    }
                }
            }
            $token = $tokenData->token;
            if($remember_me) {
             $token->expires_at = Carbon::now()->addWeeks(1);
            }
            if($token->save()){
                return response()->json([
                    "user" => $user,
                    "status" => true,
                    "token_type" =>'Bearer',
                    "token"=> $tokenData->accessToken,
                    "token_scope" => $token->scopes[0],
                    "expires_at" => Carbon::parse($token->expires_at)->toDateTimeString(),
                    "status_code" => 201
                ],201);
            }else {
                return $this->HandleResponse(false,"Something Went Wrong, Please Try Agian",201);
            }

        }
    }


    public function logout(Request $request){
        $user = $request->user()->token()->revoke();
        if($user){
          return $this->HandleResponse(true,"Logout SuccessFully",201);
    }else {
        return $this->HandleResponse(false,"Error Occure in Logout",201);
    }
    }
}

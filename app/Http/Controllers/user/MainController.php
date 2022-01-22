<?php

namespace App\Http\Controllers\user;
use App\user;
use Illuminate\Http\Request;
use App\Http\Triats\GeneralTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validator;




class MainController extends Controller
{
    use GeneralTrait;
    public function login(Request $request){
   // validate

    $rules = [
        'email' => 'required | exists:users,email',
        'password' => 'required'
    ];

    $validator =validator::make($request->all(),$rules);
         if($validator->fails()){
         $code =$this->returnCodeAccordingToInput($validator);
         return $this->returnValidationError($code,$validator);
         }
    //login
    $credentials =$request->only(['email','password']);
    $token =Auth::guard('user-api')->attempt($credentials);
    $user =Auth::guard('api')->user(); 
    $user -> api_token =$token ;

    if(!$token)
        return $this->returnError('E001','invalid data');

    
    // return token
        return $this->returnData('user',$user);
    }    

    public function logout(Request $request){
        $token =$request->header('auth-token');
        if($token){
            try{
                JWTAuth::setToken($token)->invalidate();
            }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return $this->returnError("E000",'some things went wrongs');
            }
                return $this->returnSuccessMessage('logged out successfully');
        }
        else{
            return $this->returnError('some things went wrongs');
        }
    }

    public function UserRegister(Request $request){
        //validate 
        $ruels =[
            'name' => 'required'| 'max:100' ,
            'email' => 'required' | 'email' |'unique:users,email',    
            'password' => 'required'| 'min :8'
        ];

        $validator =validator::make($request->all(),$ruels);
        if(!$validator){
            $code= response()->json($validator);
            return response()->json([$code,$validator]);
        }


        user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'=>bcrypt($request->password),

        ]);

        return response()->json('saved successfully');



    }


}

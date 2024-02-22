<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use GeneralTrait;
    public function index(){
        $user=User::selection()->get();
        return $this->ReturnData('Users',$user,'');

    }
    public function register(Request $request)
    {
        try {
            //rules
            $rules = [
                'fname' => 'required|between:2,100',
                'lname' => 'required|between:2,100',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|min:6',
                'age' => 'required|int',
                'gender' => 'required|between:2,100',
                'phone' => 'required|between:2,100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            //confirm password
            if ($request->password != $request->com_password)
            {
                return $this->ReturnError(400, __('msgs.please confirm password'));
            }
            if ($request->hasFile('photo'))
            {
                $pathFile = uploadImage('user', $request->photo);
                 User::create([
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'age' => $request->age,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'city' => $request->city,
                    'country' => $request->country,
                    'photo' => $pathFile,
                ]);
                return $this->ReturnSuccess(200, __('msgs.user created successfully'));
            }
            else
            {
                 User::create([
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'age' => $request->age,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'city' => $request->city,
                    'country' => $request->country,
                     'photo' => null,

                    ]);
                return $this->ReturnSuccess(200, __('msgs.user created successfully'));

            }

        }
        catch (\Exception $ex)
{
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            //validation
            $rules = [
                'email' => 'required|email',
                'password' => 'required',

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            ///////  login  ////
            $incremental=$request->only(['email','password']);
            $token=Auth::guard('user-api')->attempt($incremental);
            if (!$token)
            {
                return $this->ReturnError('E001',__('msgs.information'));
            }
            $user=Auth::guard('user-api')->user();
            $user->api_token=$token;
            return $this->ReturnData('user',$user,__('msgs.enter'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $token=$request->header('auth_token');
            if ($token){
                try {
                    JWTAuth::setToken($token)->invalidate();

                }catch (TokenInvalidException $ex){
                    return $this->ReturnError('E000',__('msgs.something'));
                }
            return $this->ReturnSuccess('200',__('msgs.logout'));
            }
            else{
                return $this->ReturnError('E000',__('msgs.something'));
            }
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
}

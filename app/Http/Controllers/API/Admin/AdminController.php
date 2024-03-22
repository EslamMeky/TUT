<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use GeneralTrait;

    public function register(Request $request)
    {
        try
        {
            //rules
            $rules = [
                'fname' => 'required|between:2,100',
                'lname' => 'required|between:2,100',
                'email' => 'required|email|max:200|unique:admins',
                'password' => 'required|min:6',
                'gender' => 'required|between:2,100',
                'phone' => 'required|between:2,11',
                'photo'=>'required|mimes:jpg,jpeg,png'
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
                $pathFile = uploadImage('admin', $request->photo);
                Admin::create([
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'photo' => $pathFile,
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
            $token=Auth::guard('admin-api')->attempt($incremental);
            if (!$token)
            {
                return $this->ReturnError('E001',__('msgs.information'));
            }
            $user=Auth::guard('admin-api')->user();
            $user->api_token=$token;
            return $this->ReturnData('admin',$user,__('msgs.enter'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function show(Request $request)
    {
        try
        {
            $admins=Admin::selection()->paginate(PAGINATE);
            return $this->ReturnData('Admins',$admins,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function edit($id)
    {
        $admin=Admin::find($id);
        if (!$admin){
            return $this->ReturnError('E000',__('msgs.notFound'));

        }
        $admin->where('id',$id)->get();
        return $this->ReturnData('admin',$admin,'success');
    }

    public function update(Request $request,$id)
    {
        try {
            $admin=Admin::find($id);
            if (!$admin){
                return $this->ReturnError('E000',__('msgs.notFound'));
            }
            $admin->where('id',$request->id)->update([
                'fname'=>$request->fname,
                'lname'=>$request->lname,
                'email'=>$request->email,
                'gender'=>$request->gender,
                'phone'=>$request->phone,
            ]);
            if ($request->hasFile('photo')){
                $pathFile=uploadImage('admin',$request->photo);
                Admin::where('id',$id)->update([
                    'photo'=>$pathFile,
                ]);
            }
            return $this->ReturnSuccess('200',__('msgs.updateUser'));

        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }


    public function delete(Request $request ,$id){
        try
        {
            $admin=Admin::find($id);
            if (!$admin){
                return $this->ReturnError('E000',__('msgs.notFound'));
            }
            if ($admin->photo != null){
                $image=Str::after($admin->photo,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $admin->delete();
            }
            else
                $admin->delete();
            return $this->ReturnSuccess('S00',__('msgs.delete'));

        }
        catch(\Exception $ex )
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }





}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GeneralTrait;

    public function edit($id)
    {
        $user=User::find($id);
        if (!$user){
            return $this->ReturnError('E000',__('msgs.notFound'));

        }
        $user->where('id',$id)->get();
        return $this->ReturnData('user',$user,'success');
    }
   public function update(Request $request,$id)
   {
       try {
           $user=User::find($id);
           if (!$user){
               return $this->ReturnError('E000',__('msgs.notFound'));
           }
           $user->where('id',$request->id)->update([
               'fname'=>$request->fname,
               'lname'=>$request->lname,
               'email'=>$request->email,
               'age'=>$request->age,
               'gender'=>$request->gender,
               'phone'=>$request->phone,
               'city'=>$request->city,
               'country'=>$request->country,
           ]);
           if ($request->hasFile('photo')){
            $pathFile=uploadImage('user',$request->photo);
            User::where('id',$id)->update([
               'photo'=>$pathFile,
            ]);
           }
           return $this->ReturnSuccess('200',__('msgs.updateUser'));

       }
       catch (\Exception $ex){
        return $this->ReturnError($ex->getCode(),$ex->getMessage());
       }
   }
}

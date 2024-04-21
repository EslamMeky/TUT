<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

   public function delete(Request $request ,$id){
       try
       {
           $user=User::find($id);
           if (!$user){
               return $this->ReturnError('E000',__('msgs.notFound'));
           }
           if ($user->photo != null){
               $image=Str::after($user->photo,'assets/');
               $image=base_path('public/assets/'.$image);
               unlink($image);
               $user->delete();
           }
           else
               $user->delete();
           return $this->ReturnSuccess('S00',__('msgs.delete'));

       }
       catch(\Exception $ex )
       {
           return $this->ReturnError($ex->getCode(),$ex->getMessage());
       }
   }


   public function detailsUser (Request $request)
   {
       try
       {

           ////////////////////  GET  Each one alone  HOTEL RESTAURANT  PLACETOGO  /////////
           $user_id = $request->user_id;

           $user = User::find($user_id);
           if (!$user) {
               return $this->ReturnError('E00', 'User not found');
           }

           $restaurants = User::with(['favorites.places' => function($query) {
               $query->with('cities')->where('category_name', 'Restaurant');
           }])
               ->where('id', $user_id)
               ->orderBy('id', 'desc')
               ->paginate(PAGINATE);

           $hotel = User::with(['favorites.places' => function($query) {
               $query->with('cities')->where('category_name', 'Hotel');
           }])
               ->where('id', $user_id)
               ->orderBy('id', 'desc')
               ->paginate(PAGINATE);

           $placeToGo = User::with(['favorites.places' => function($query) {
               $query->with('cities')->whereNotIn('category_name', ['Hotel', 'Restaurant']);
           }])
               ->where('id', $user_id)
               ->orderBy('id', 'desc')
               ->paginate(PAGINATE);

           $data = [
               'User hotels' => $hotel,
               'User restaurants' => $restaurants,
               'User placeToGo' => $placeToGo,
           ];

           return $this->ReturnData('MultipleData', $data, 'done');

///////     GET ALL DATA  HOTEL RETURANTE PLACETOGO /////



//            $user=User::with('favorites.places.cities')->find($request->user_id);
//            if (!$user){
//                return $this->ReturnError('E00','Not Found This User');
//            }
//            return $this->ReturnData('user',$user,'done');

       }
       catch (\Exception $ex)
       {
           return $this->ReturnError($ex->getCode(),$ex->getMessage());
       }
   }
}

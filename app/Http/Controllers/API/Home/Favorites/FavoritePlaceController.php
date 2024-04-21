<?php

namespace App\Http\Controllers\API\Home\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritePlaceController extends Controller
{
    use GeneralTrait;
    public function addPlace(Request $request)
    {
        try
        {
            //rules
            $rules = [
                'user_id' => 'required',
                'place_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

                Favorite::create([
                    'user_id' => $request->user_id,
                    'place_id' => $request->place_id,

                ]);
                return $this->ReturnSuccess(200, __('msgs.user created successfully'));


        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try
        {
            $favorites = Favorite::with(['users','places.cities'])->paginate(PAGINATE);
            return $this->ReturnData('Favorites',$favorites,'done');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function getFavoritePlaces(Request $request)
    {
        try
        {
            $favorite=Favorite::with(['users','places.cities'])->where('user_id',$request->user_id)->get();
            return $this->ReturnData('favorite',$favorite,'done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function deleteFavorite(Request $request)
    {
        try
        {
            $user_id=$request->user_id;
            $place_id=$request->place_id;
            $deleteFavorite=Favorite::where('user_id',$user_id)
                ->where('place_id',$place_id)->delete();

            if ($deleteFavorite){
                return $this->ReturnSuccess('200','Deleted Place Done Favourite');
            }
            else
            {
             return $this->ReturnError('E00','Not Found This Place In Favourite');
            }
        }
        catch (\Exception $ex )
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

}

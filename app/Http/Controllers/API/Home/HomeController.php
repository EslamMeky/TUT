<?php

namespace App\Http\Controllers\API\Home;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Place;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use  GeneralTrait;
    public function index()
    {
        try
        {
            $exceptionCategory=['Hotel','Restaurant'];

            $Restaurant=Place::with('cities')->where('category_name','Restaurant')->orderBy('id','desc')->paginate(PAGINATE);
            $Hotel=Place::with('cities')->where('category_name','Hotel')->orderBy('id','desc')->paginate(PAGINATE);
            $PlaceToGo=Place::with('cities')->whereNotIn('category_name',$exceptionCategory)->orderBy('id','desc')->paginate(PAGINATE);

            $data = [
                'Hotel' => $Hotel,
                'Restaurant' => $Restaurant,
                'PlaceToGo'=>$PlaceToGo,
            ];

            return $this->ReturnData('MultipleData', $data, 'done');

        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }


    public function city(Request $request)
    {
        try
        {
            $city =Place::with('cities')->where('city_id',$request->id)->orderBy('id','desc')->get();
            return $this->ReturnData('City',$city,'done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function place(Request $request)
    {
        try
        {
            $place = Place::with('cities')
                ->where('id', $request->id)
                ->first();

            if ($place) {
                $categoryName = $place->category_name;

                $similarPlaces = Place::with('cities')
                    ->where('category_name', $categoryName)
                    ->where('id', '!=', $request->id) // لا يجب أن تكون نفس المكان
                    ->inRandomOrder()
                    ->take(10) // احصل على 10 أماكن فقط
                    ->get();

                $data=[
                  'Place'=>$place,
                  'Also Like'=>$similarPlaces
                ];

                return $this->ReturnData('Data', $data, 'done');
            } else {
                return $this->ReturnError('E00', 'Place not found');
            }

//            $place=Place::with('cities')->where('id',$request->id)->get();
//            return $this->ReturnData('Place',$place,'done');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }


    public function SeeMoreRestaurant()
    {
        try
        {
            $Restaurant=Place::with('cities')->where('category_name','Restaurant')->orderBy('id','Asc')->Paginate(PAGINATE);
            return $this->ReturnData('Restaurant',$Restaurant,'done');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function SeeMoreHotel()
    {
        try
        {
            $Hotel=Place::with('cities')->where('category_name','Hotel')->orderBy('id','Asc')->Paginate(PAGINATE);
            return $this->ReturnData('Hotel',$Hotel,'done');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function SeeMorePlaceToGo()
    {
        try
        {
            $exceptionCategory=['Hotel','Restaurant'];

            $placeToGo=Place::with('cities')->whereNotIn('category_name',$exceptionCategory)->orderBy('id','Asc')->Paginate(PAGINATE);
            return $this->ReturnData('PlaceToGo',$placeToGo,'done');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

}

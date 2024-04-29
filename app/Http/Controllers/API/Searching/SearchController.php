<?php

namespace App\Http\Controllers\API\Searching;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Place;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class SearchController extends Controller
{
    use GeneralTrait;

//    function binarySearch($names, $target) {
//        $left = 0;
//        $right = count($names) - 1;
//
//        while ($left <= $right) {
//            $mid = floor(($left + $right) / 2);
//
//            // يقوم بمقارنة القيمة المستهدفة مع القيمة في الموقع المتوسط
//            $comparison = strcmp($names[$mid]->name, $target);
//
//            // إذا كانت القيمة المستهدفة تساوي القيمة الموجودة في الموقع المتوسط
//            if ($comparison == 0) {
//                return $mid; // تم العثور على القيمة
//            }
//
//            // إذا كانت القيمة المستهدفة أكبر من القيمة في الموقع المتوسط، ابحث في الجزء الأيمن من المصفوفة
//            if ($comparison < 0) {
//                $left = $mid + 1;
//            }
//            // إذا كانت القيمة المستهدفة أصغر من القيمة في الموقع المتوسط، ابحث في الجزء الأيسر من المصفوفة
//            else {
//                $right = $mid - 1;
//            }
//        }
//
//        return -1; // لم يتم العثور على القيمة
//    }
    public function admin(Request $request)
    {
//        $admins=Admin::select('fname')->orderBy('fname')->get();
//
//        $target=$request->fname;
//        $search=$this->binarySearch($admins,$target);
//        if ($search != -1)
//        {
////            return $this->ReturnSuccess('200','Done Search');
//            return $this->ReturnData('names',$search,'200');
//        }else
//        {
//            return $this->ReturnError('E000','Not Found This');
//
//        }

        // تنفيذ البحث الباينري باستخدام استعلام SQL مباشر
//        $admins = DB::table('admins')
//            ->whereRaw("BINARY `fname` = '$search'")
//            ->get();
        $search = $request->search;

        $admins=Admin::where('fname','LIKE',"%$search%")
        ->orWhere('lname','LIKE',"%$search%")->get();
        if ($admins -> isEmpty())
        {
//            return $this->ReturnError('E00','No Admins Found  Matching  the search');
            return $this->ReturnData('Admins',$admins,'Not Found');

        }
        return$this->ReturnData('Admins',$admins,'done search');
//      return $this->ReturnSuccess('200','done search');
    }



    public function user(Request $request)
    {
        $search=$request->search;
        $users=User::where('fname','LIKE',"%$search%")
            ->orWhere('lname','LIKE',"%$search%")->get();
        if ($users -> isEmpty())
        {
//            return $this->ReturnError('E00','No Users Found  Matching  the search');
            return $this->ReturnData('users',$users,'Not Found');
        }
        return $this->ReturnData('users',$users,'Done search');
    }

    public function city(Request $request)
    {
        $search=$request->search;

        $city=City::where('name','LIKE',"%$search%")->get();
        if ($city -> isEmpty()){
//            return $this->ReturnError('E00','No Cities Found  Matching  the search');
            return $this->ReturnData('city',$city,'Not Found');

        }
        return $this->ReturnData('city',$city,'Done search');

    }

    public function category(Request $request)
    {
        $search=$request->search;

        $category=Category::where('name','LIKE',"%$search%")->get();
        if ($category -> isEmpty()){
//            return $this->ReturnError('E00','No Categories Found  Matching  the search');
            return $this->ReturnData('category',$category,'Not Found');

        }
        return $this->ReturnData('categories',$category,'Done search');

    }

    public function places(Request $request)
    {
        $search=$request->search;

        $places=Place::where('name','LIKE',"%$search%")->get();
        if ($places -> isEmpty()){
//            return $this->ReturnError('E00','No Places Found  Matching  the search');
            return $this->ReturnData('places',$places,'Not Found');

        }
        return $this->ReturnData('places',$places,'Done search');

    }

    public function rate(Request $request)
    {
        $search=$request->search;

        $rates=Rating::with(['users'])->whereHas('users',function ($query)  use ($search){
            $query->where('fname','LIKE',"%$search%")
            ->orWhere('lname','LIKE' ,"%$search%");
        })->get();
        if ($rates -> isEmpty()){
//            return $this->ReturnError('E00','No Rates Found  Matching  the search');
            return $this->ReturnData('rates',$rates,'Not Found');

        }
        return $this->ReturnData('rates',$rates,'Done search');

    }

    ///AI Search With Similarity////
    public function recommendPlaces(Request $request)
    {
        try {
            $placeName = $request->input('place_name');
            $client = new \GuzzleHttp\Client();
            $response = $client->get('http://127.0.0.1:5000/recommend_places', [
                'query' => [
                    'place_name' => $placeName,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $recommendedPlaces = json_decode($response->getBody()->getContents(), true);
                $placesData = [];

                if (empty($recommendedPlaces)) {
                    // Perform regular search query using %LIKE%
                    $places = Place::with('cities')->where('name', 'LIKE', '%' . $placeName . '%')->get();
                    foreach ($places as $place) {
                        $placesData[] = $place;
                    }
                } else {
                    foreach ($recommendedPlaces as $recommendedPlace) {
                        $place = Place::with('cities')->where('name', $recommendedPlace['Name'])->first();
                        if ($place) {
                            $placesData[] = $place;
                        }
                    }
                }

                return $this->ReturnData('Search', $placesData, 'Search generated successfully.');
            }
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }
}

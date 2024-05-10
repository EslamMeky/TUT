<?php

namespace App\Http\Controllers\API\Home\AI;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Validator;

class RecommendationController extends Controller
{
    use GeneralTrait;

//    public function getRecommendations(Request $request)
//    {
//        try {
//            $categories = $request->input('categories');
//
//            $client = new \GuzzleHttp\Client();
//            $response = $client->post('http://127.0.0.1:5000/filter-places', [
//                'json' => ['categories' => $categories]
//            ]);
//
//            $recommendations = json_decode($response->getBody(), true)['top_places'];
//
//            $placesWithDetails = [];
//            foreach ($recommendations as $placeName) {
//                $place = Place::with('cities')->where('name', $placeName)->first();
//                if ($place) {
//                    $placesWithDetails[] = $place;
//                }
//            }
//            return $this->ReturnData('Recommendations', $placesWithDetails, 'Recommendations generated successfully.');
//        }catch (\Exception $ex) {
//            return $this->ReturnError($ex->getCode(), $ex->getMessage());
//        }
//    }

    public function getRecommendations(Request $request)
    {
        try {
            $categories = $request->input('categories');
            $userId = $request->input('user_id');

            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://127.0.0.1:5000/filter-places', [
                'json' => ['categories' => $categories]
            ]);

            $recommendations = json_decode($response->getBody(), true)['top_places'];

            foreach ($recommendations as $placeName) {
                $place = Place::where('name', $placeName)->first();
                if ($place) {
                    Recommendation::create([
                        'user_id' => $userId,
                        'place_id' => $place->id,
                    ]);
                }
            }
            return $this->ReturnSuccess('200','Successfully stored Recommendations');
        } catch (\Exception $ex)
        {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function show($UserId)
    {
        try {
            $recommendations = Recommendation::with(['users', 'places.cities'])->where('user_id',$UserId)->get();

            return $this->ReturnData('Recommendations',$recommendations,'Recommendations fetched successfully.');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

}


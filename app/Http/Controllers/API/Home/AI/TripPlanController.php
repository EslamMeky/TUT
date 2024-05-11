<?php

namespace App\Http\Controllers\API\Home\AI;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Place;
use App\Models\Trip;
use App\Models\TripPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripPlanController extends Controller
{
    use GeneralTrait;
    public function generate(Request $request)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://127.0.0.1:5000/trip_plan', [
                'json' => $request->all(),
            ]);

            $data = json_decode($response->getBody(), true);
            $tripPlan = $data['trip_plan'];

            $placesWithDetails = [];
            foreach ($tripPlan as $day => $places) {
                $placesDetails = [];
                foreach ($places as $placeName) {
                    $place = Place::with('cities')->where('name', $placeName)->first();
                    if ($place) {
                        $placesDetails[] = $place;
                    }
                }
                $placesWithDetails[$day] = $placesDetails;
            }

            return $this->ReturnData('TripPlanController', $placesWithDetails, 'Trip plan generated successfully.');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'days' => 'required',
                'city' => 'required',
                'places' => 'required|array', // Ensure places is provided as an array
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }

            $trip = Trip::create([
                'user_id' => $request->user_id,
                'days' => $request->days,
                'city' => $request->city,
            ]);

            foreach ($request->places as $placeName => $placesForDay) {
                if ($placeName === "Recommended Hotel") {
                    $day = 0; // Special case for "Recommended Hotel"
                } else {
                    $day = intval(preg_replace('/[^0-9]/', '', $placeName)); // Extract day number from placeName
                    if ($day < 1 || $day > $request->days) {
                        return response()->json(['error' => 'Invalid day provided.'], 422);
                    }
                }

                if (!is_array($placesForDay)) {
                    return response()->json(['error' => 'Places for ' . $placeName . ' must be provided as an array.'], 422);
                }

                foreach ($placesForDay as $placeId) {
                    // Validate placeId
                    if (!is_numeric($placeId)) {
                        return response()->json(['error' => 'Invalid place ID provided for ' . $placeName], 422);
                    }

                    TripPlace::create([
                        'trip_id' => $trip->id,
                        'day_num' => $day,
                        'place_id' => $placeId,
                    ]);
                }
            }

            return response()->json(['message' => 'Successfully stored trip'], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function showTrip($tripId)
    {
        try {
            $trip = TripPlace::with(['trip.users', 'place'])->where('trip_id',$tripId)->get();

            return $this->ReturnData('TripPlaces',$trip,'Trip details fetched successfully.');
//            return response()->json([
//                'status' => true,
//                'errNum' => 200,
//                'msg' => 'Trip details fetched successfully.',
//                'tripPlaces' => $trip,
//            ]);
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

}



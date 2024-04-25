<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    use GeneralTrait;

    public function add(Request $request)
    {
        try
        {
            ////  validation  ///
            $rules = [
                'user_id' => 'required',
                'place_id' => 'required',
                'rating' => 'required',
                'review'=>'required',


            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            /////  add  ///
            if (!$request->has('status'))
                $request->request->add(['status' => 1]);
            else
                $request->request->add(['status' => 0]);

            Rating::create([
                'user_id'=>$request->user_id,
                'place_id'=>$request->place_id,
                'rating'=>$request->rating,
                'review'=>$request->review,
                'status'=>$request->status,
            ]);
            return $this->ReturnSuccess('S000', __('msgs.add'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }

    public function show()
    {
        try
        {
            $rating=Rating::with(['users','places'])->selection()->latest()->paginate(PAGINATE);
            return $this->ReturnData('Rating',$rating,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function delete($id)
    {

        try
        {
            $rating=Rating::find($id);
            if (!$rating){
                return $this->ReturnError('E000',__('msgs.not'));
            }
            $rating->delete();
            return $this->ReturnSuccess('S00',__('msgs.delete'));

        }
        catch(\Exception $ex )
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function getUserPlace($user_id,$place_id)
    {
        try
        {
            $rating = Rating::with(['users','places'])->where(['user_id'=>$user_id,'place_id'=>$place_id])->get();
            return $this->ReturnData('RatingUser',$rating,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }

    public function showAllRatingPlace($place_id)
    {

        try
        {
            $rating = Rating::with(['users'])->where(['status'=>1,'place_id'=>$place_id])->latest()->get();
            return $this->ReturnData('RatingPlace',$rating,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }


    public function getAverageRatingOfPlace($place_id)
    {
        try
        {
            $ratingSum =Rating::where(['status'=>1,'place_id'=>$place_id])->sum('rating');

            $ratingCount = Rating::where(['status'=>1,'place_id'=>$place_id])->count();
            if ($ratingCount>0){
                $avgRating=round( $ratingSum / $ratingCount ,2);

            }
            else{
                $avgRating=0;
            }



            return $this->ReturnData('avgRating',$avgRating,'Done');

        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }


}

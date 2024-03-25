<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PlacesController extends Controller
{
    use GeneralTrait;

    public function add(Request $request)
    {
        try
        {
            ////  validation  ///
            $rules = [
                'name' => 'required|between:2,100',
                'desc' => 'required',
                'photo'=>'required|mimes:jpg,jpeg,png',
                'city_id'=>'required',
                'category_name'=>'required',
                'longitude'=>'required',
                'latitude'=>'required',

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            /////  add  ///
            $pathFile = uploadImage('place', $request->photo);

            Place::create([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'photo'=>$pathFile,
                'city_id'=>$request->city_id,
                'category_name'=>$request->category_name,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
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
            $place=Place::with(['cities'])->selection()->latest()->paginate(PAGINATE);
            return $this->ReturnData('Places',$place,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function edit($id)
    {
        $place=Place::find($id);
        if (!$place){
            return $this->ReturnError('E000',__('msgs.not'));

        }
        $place->where('id',$id)->get();
        return $this->ReturnData('place',$place,'success');

    }


    public function update(Request $request,$id)
    {
        try {
            $place=Place::find($id);
            if (!$place){
                return $this->ReturnError('E000',__('msgs.not'));
            }
            $place->where('id',$request->id)->update([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'city_id'=>$request->city_id,
                'category_name'=>$request->category_name,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
            ]);
            if ($request->hasFile('photo')){
                $pathFile=uploadImage('place',$request->photo);
                Place::where('id',$id)->update([
                    'photo'=>$pathFile,
                ]);
            }
            return $this->ReturnSuccess('200',__('msgs.update'));

        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function delete($id)
    {

        try
        {
            $place=Place::find($id);
            if (!$place){
                return $this->ReturnError('E000',__('msgs.not'));
            }
            if ($place->photo != null){
                $image=Str::after($place->photo,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $place->delete();
            }
            else
                $place->delete();
            return $this->ReturnSuccess('S00',__('msgs.delete'));

        }
        catch(\Exception $ex )
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }





}

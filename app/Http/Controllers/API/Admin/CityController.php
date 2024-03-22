<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CityController extends Controller
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
                'photo'=>'required|mimes:jpg,jpeg,png'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            /////  add  ///
            $pathFile = uploadImage('city', $request->photo);

            City::create([
                'name'=>$request->name,
                'desc'=>$request->desc,
                'photo'=>$pathFile,
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
            $cities=City::selection()->paginate(PAGINATE);
            return $this->ReturnData('Cities',$cities,'Done');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function edit(Request $request,$id)
    {
        $city=City::find($id);
        if (!$city){
            return $this->ReturnError('E000',__('msgs.not'));

        }
        $city->where('id',$id)->get();
        return $this->ReturnData('city',$city,'success');

    }


    public function update(Request $request,$id)
    {
        try {
            $city=City::find($id);
            if (!$city){
                return $this->ReturnError('E000',__('msgs.not'));
            }
            $city->where('id',$request->id)->update([
                'name'=>$request->name,
                'desc'=>$request->desc,
            ]);
            if ($request->hasFile('photo')){
                $pathFile=uploadImage('city',$request->photo);
                City::where('id',$id)->update([
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
            $city=City::find($id);
            if (!$city){
                return $this->ReturnError('E000',__('msgs.not'));
            }
            if ($city->photo != null){
                $image=Str::after($city->photo,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $city->delete();
            }
            else
                $city->delete();
            return $this->ReturnSuccess('S00',__('msgs.delete'));

        }
        catch(\Exception $ex )
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }


}

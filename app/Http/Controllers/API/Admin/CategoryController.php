<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function add(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|between:2,100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            Category::create([
                'name' => $request->name,
            ]);

            return $this->ReturnSuccess('S000', __('msgs.add'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try {
            $categories = Category::selection()->latest()->paginate(PAGINATE);
            return $this->ReturnData('Categories', $categories, 'Done');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->ReturnError('E000', __('msgs.not'));
            }
            return $this->ReturnData('Category', $category, 'success');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->ReturnError('E000', __('msgs.not'));
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|between:2,100',
            ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $category->update([
                'name' => $request->name,
            ]);

            return $this->ReturnSuccess('S000', __('msgs.update'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->ReturnError('E000', __('msgs.not'));
            }
            $category->delete();
            return $this->ReturnSuccess('S00', __('msgs.delete'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }
}


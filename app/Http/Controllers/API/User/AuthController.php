<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use GeneralTrait;
    public function index(){
        $user=User::selection()->get();
        return $this->ReturnData('Users',$user,'');

    }
}

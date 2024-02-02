<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\JWT;

class AssignGuard extends BaseMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$guard=null)
    {
        if ($guard!=null){
            auth()->shouldUse($guard);
            $token=$request->header('auth-token');
            $request->headers->set('auth-token',(string) $token,true);
            $request->headers->set('Authorization','Bearer'.$token,true);
            try {
                // $user=$this->auth->authenticate($request);
                $user=JWTAuth::parseToken()->authenticate();
            }catch (TokenInvalidException $ex){
                return $this->ReturnError('401','Unauthenticated User');
            }catch (JWTException $e){
                return $this->ReturnError('','Token InVaild '. $e->getMessage());
            }
        }
        return $next($request);
    }
}

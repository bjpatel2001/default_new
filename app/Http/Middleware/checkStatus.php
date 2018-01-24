<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AppUser;
use Auth;

class checkStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $appUser = new AppUser();

        $response =  $appUser->checkStatus('id',Auth::user()->id);
        if(!$response){
            return response(['statusCode' =>2,'errors' => [],'message' =>["Your account is suspended temporarily, Please contact Admin.!"] ]);
        }
        $response = $next($request);
        return $response;

    }
}

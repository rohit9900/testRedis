<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AdminKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try
        {
            $key = $request->headers->get('key');

            if($key != null)
            {
                $secret_key = User::where('remember_token', $key)->count();

                if($secret_key == 0)
                {
                    return response()->json(['status'=>'error', 'message'=>'wrong secret key']);
                }

                return $next($request);
            }
            else
            {
                return response()->json(['status'=>'error', 'message'=>'Required access token key']);
            }

        }
        catch(QueryException $e)
        {
            return response()->json(['status'=>'error', 'message'=>$e->getMessage()]);
        }
        catch(Exception $e)
        {
            return response()->json(['status'=>'error', 'message'=>$e->getMessage()]);
        }
    }
}

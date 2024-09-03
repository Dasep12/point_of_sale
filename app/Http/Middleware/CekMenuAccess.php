<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CekMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $roleId = session()->get("role_id");
        $userId = session()->get("user_id");
        $menuUrl  =  request()->path();
        $data =  DB::select("SELECT enable_menu FROM  vw_sys_menu WHERE user_id = '" . $userId . "' AND MenuUrl='" . $menuUrl . "' AND role_id = '" . $roleId . "'  ");

        if ($data != null) {
            if ($data[0]->enable_menu == 0) {
                return redirect('/administrator/deny');
            }
        }
        return $next($request);
    }
}

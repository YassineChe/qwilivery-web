<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    //? Chech for authenticated guard
    public function fetchAuthenticatedGuard()
    {
        try {
            return response(
                \Auth::guard(getConnectedGuard())->user()
                ,
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}

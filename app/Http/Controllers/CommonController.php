<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    //? Chech for authenticated guard
    public function fetchAuthenticatedGuard(Request $request)
    {

        try {
            return response(
                \Auth::guard(getConnectedGuard())->user() //->only('first_name', 'last_name', 'email'));
                ,
                200
            );
        } catch (\Exception $e) {
            handleLogs($e);
        }
    }
}

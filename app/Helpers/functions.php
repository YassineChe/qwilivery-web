<?php

//? This is for custom Error/Success message
function dataToResponse($type, $message, $subMessage, $status, $complete = true)
{
    return response()->json(
        [
            'type'       => $type, // type of response {error, success, warning,}
            'message'    => $message, // Message Headline
            'subMessage' => $subMessage, // sub messages (can be an array).
            'complete'   => $complete // true or false.
        ],
        $status // status code {200, 404, 422...}
    );
}


//? Store uploaded file
function storeUploaded($path, $file)
{
    try {
        $to = time() . '.' . $file->extension();
        // Move picture to server
        $file->move($path, $to);
        return  $to;
    } catch (\Exception $e) {
        \Log::error($e);
        return null;
    }
}

//? ID of authenticated guard
function authIdFromGuard($guard)
{
    try {
        return Auth::guard($guard)->user()->id;
    } catch (\Exception $e) {
        return null;
    }
}

//? Log errors
function handleLogs($e)
{
    \Log::error($e);
}

//? Will get the guard data
function guardData($guard){
    try{
        return Auth::guard($guard)->user();
    }
    catch(\Exception $e){
        return null;
    }
}

//? Guard ID from field
function guardIdField()
{
    if (Auth::guard('admin')->check()) {
        return 'admin_id';
    } elseif (Auth::guard('shipper')->check()) {
        return 'shipper_id';
    } elseif (Auth::guard('commercial')->check()) {
        return 'commercial_id';
    } else
        return null;
}

//? Get connected guard
function getConnectedGuard()
{
    if (Auth::guard('admin')->check()) {
        return 'admin';
    } elseif (Auth::guard('delivery')->check()) {
        return 'delivery';
    } elseif (Auth::guard('restaurant')->check()) {
        return 'restaurant';
    } else
        return null;
}

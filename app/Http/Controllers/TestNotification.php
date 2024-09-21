<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GGInnovative\Larafirebase\Facades\Larafirebase;

class TestNotification extends Controller
{
    public function index()
    {
        return Larafirebase::withTitle('Hello World')
            ->withBody('I have something new to share with you!')
            ->withToken('d0CaCovoQZeiTO99Ws4iuT:APA91bEuN2hzyr1jv8GHFi-1Pj98MWwvuF7nv1YiaRzIFDoF9FvIppakzo_kcYxfD0nFq7elGZjv-TbxjFE-cAO6NyKVEStaVntmjyXspygbgKsGurkaxyPkMpdlh-X52FLknvUNSPgp') // You can use also withTopic
            ->sendNotification();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifycationController extends Controller
{
    public static function notifycation() {
        return view('Pages.notifycation');
    }
}

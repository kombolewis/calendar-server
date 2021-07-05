<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Return logged in user schedule
     *
     * @return mixed
     */
    public function read() {

        return User::where('id', auth()->user()->id)->with(['events', 'events.schedules'])->get();
    }
}

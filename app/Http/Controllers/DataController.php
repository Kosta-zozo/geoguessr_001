<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mytable;

class DataController extends Controller
{
    public function test() {
        $data = new mytable();
        return view('dbtest', ['data' => $data->get()]);
    }
    public function game() {
        $data = new mytable();
        return view('game', ['data' => $data->get()]);
    }
}

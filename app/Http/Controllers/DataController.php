<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\places;
use App\Models\results;
use App\Models\User;

class DataController extends Controller
{
    public function game() {
        $data['places'] = (new places())->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();
        return view('game', ['data' => $data, 'resultView' => false]);
    }
    public function submitResult(request $data) {
        results::insert([
        'place_id' => $data['place_id'],
        'user_id' => $data['user_id'],
        'result' => $data['result'],
        'wasted_time' => $data['wasted_time'],
        'created_date' => $data['created_date']
        ]);

        $data['places'] = (new places())->get();
        $data['results'] = (new results())->join('users', 'users.id', '=', 'results.user_id')
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();
        // return view('home', ['data' => $data->get()]);
        // return redirect()->to('/resultPreview');
        return view('game', ['data' => $data, 'resultView' => true]);
    }
    public function addPlace(request $request) {
        $request->validate([
            'image' => ['image'],
        ]);
        $path = $request->file('image')->store('public');
        return $path;
        $avatar = $request->file('image')->store('public');
        // $avatar = Storage::disk('public')->put('/',$request->file('image'));

        // if(!Storage::disk('public_uploads')->put('image.txt', $data['image'])) {
        //     return false;
        // }
        // return redirect()->to('/adminpanel');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\places;
use App\Models\results;
use App\Models\categories;
use App\Models\countries;
use App\Models\User;

class DataController extends Controller
{
    public function game() {
        $data['places'] = (new places())->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();
        return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => false]);
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
        return view('game', ['data' => $data, 'resultView' => true, 'gameSerie' => false]);
    }
    public function gameStartSerie() {
        $data['places'] = (new places())->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();
        $usedIdArray = [];
        return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => 3, 'usedIdArray' => $usedIdArray, 'difficulty' => 'random']);
    }
    public function gameStartSerieEasy() {
        return $this->gameStartSerieDiff('easy');
    }
    public function gameStartSerieMedium() {
        return $this->gameStartSerieDiff('medium');
    }
    public function gameStartSerieHard() {
        return $this->gameStartSerieDiff('hard');
    }
    public function gameStartSerieDiff($difficulty) {
        $data['places'] = (new places())->where('difficulty','=',$difficulty)->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();
        $usedIdArray = [];
        return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => 3, 'usedIdArray' => $usedIdArray, 'difficulty' => $difficulty]);
    }
    public function gameContinueSerie(Request $data) {
        results::insert([
        'place_id' => $data['place_id'],
        'user_id' => $data['user_id'],
        'result' => $data['result'],
        'wasted_time' => $data['wasted_time'],
        'created_date' => $data['created_date']
        ]);

        if ($data['difficulty'] == 'random')
            $data['places'] = (new places())->get();
        else
            $data['places'] = (new places())->where('difficulty','=',$data['difficulty'])->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'asc')
                                          ->get();

        $serieCount = $data['serieCount'] - 1;
        if ($data['usedIdArray'] != 'none') $usedIdArray = explode(",",$data['usedIdArray']);
        $usedIdArray[] = $data['place_id'];
        if ($serieCount <= 0)
            return redirect()->to('/home');
        else
            return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => $serieCount, 'usedIdArray' => $usedIdArray, 'difficulty' => $data['difficulty']]);
    }
    public function addNewPlace() {
        $countries = (new countries())->get();
        $categories = (new categories())->get();
        return view('/addnewplace', ['countries' => $countries, 'categories' => $categories]);
    }
    public function addPlace(request $data) {
        
    
        $validated = $data->validate([
            'image' => 'required',
            'posx' => 'required|gt:0|lt:100',
            'posy' => 'required|gt:0|lt:100',
            'country' => 'required',
            'category' => 'required',
            'difficulty' => 'required',
        ]);
        $path = Storage::disk('public_uploads')->put('img', $data['image']);
        $imageName = basename($path);

        places::insert([
        'image_name' => $imageName,
        'pos_X_perc' => $data['posx'],
        'pos_Y_perc' => $data['posy'],
        'country_id' => $data['country'],
        'category_id' => $data['category'],
        'difficulty' => $data['difficulty']
        ]);
        return redirect()->to('/addNewPlace')->with('message','New place added successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\places;
use App\Models\results;
use App\Models\serie_results;
use App\Models\categories;
use App\Models\category_place_connections;
use App\Models\countries;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function gameHub() {
        $categories = categories::join('places', 'category_id', '=', 'categories.id')
            ->groupBy('category_id')
            ->havingRaw('COUNT(category_id) >= 5')
            ->select('category_id', 'categories.name')
            ->get();
        return view('gamehub', ['categories' => $categories]);
    }
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
                                          ->orderBy('results.result', 'desc')
                                          ->get();
        $usedIdArray = [];
        $resultArray = [];
        return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => 5, 'usedIdArray' => $usedIdArray, 'category' => 'random', 'resultArray' => $resultArray]);
    }
    // public function gameStartSerieEasy() {
    //     return $this->gameStartSerieDiff('easy');
    // }
    // public function gameStartSerieMedium() {
    //     return $this->gameStartSerieDiff('medium');
    // }
    // public function gameStartSerieHard() {
    //     return $this->gameStartSerieDiff('hard');
    // }
    public function gameStartSerieDiff($category, $difficulty) {
        $data['places'] = (new places())->where('category_id','=',$category)->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'desc')
                                          ->get();
        $usedIdArray = [];
        $resultArray = [];
        return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => 5, 'usedIdArray' => $usedIdArray, 'difficulty' => $difficulty, 'category' => $category, 'resultArray' => $resultArray]);
    }
    public function gameContinueSerie(Request $data) {
        results::insert([
        'place_id' => $data['place_id'],
        'user_id' => $data['user_id'],
        'result' => $data['result'],
        'distance' => $data['distance'],
        'wasted_time' => $data['wasted_time'],
        'created_date' => $data['created_date']
        ]);

        $this->cutTail($data['user_id'], $data['place_id']);

        if ($data['category'] == 'random')
            $data['places'] = (new places())->get();
        else
            $data['places'] = (new places())->where('category_id','=',$data['category'])->get();
        $data['results'] = (new results())->distinct()->join('users', 'users.id', '=', 'results.user_id') // ->distinct() for unique values
                                        //   ->select('Uzdevumi.*', 'Personazi.Vards AS Personazs')
                                          ->orderBy('results.result', 'desc')
                                          ->get();

        $serieCount = $data['serieCount'] - 1;
        if ($data['usedIdArray'] != 'none') $usedIdArray = explode(",",$data['usedIdArray']);
        $usedIdArray[] = $data['place_id'];
        if ($data['resultArray'] != 'none')
        {
            $tempArray = explode(",",$data['resultArray']);
            $i = true;
            foreach ($tempArray as $result)
            {
                if ($i) {$tempResult = $result; $i = false;}
                else {$resultArray[] = [$tempResult, $result]; $i = true;}
            }
        }
        $resultArray[] = [$data['result'], $data['wasted_time']];
        if ($serieCount <= 0)
            {
                $finalresult = 0;
                foreach ($resultArray as $result)
                {
                    $finalresult += $result[0];
                }
                serie_results::insert([
                    'user_id' => Auth::user()->id,
                    'result' => $finalresult
                ]);
                return view('results', ['resultArray' => $resultArray]);
            }
        else
            return view('game', ['data' => $data, 'resultView' => false, 'gameSerie' => true, 'serieCount' => $serieCount, 'usedIdArray' => $usedIdArray, 'difficulty' => $data['difficulty'], 'category' => $data['category'], 'resultArray' => $resultArray]);
    }
    public function cutTail(int $userId, int $placeId) {
        $count = results::count();
        results::where('place_id', '=', $placeId)
            ->where('user_id', '=', $userId)
            ->orderBy('result', 'asc')
            ->take($count)
            ->skip(10)
            ->get()
            ->each(function($row){ $row->delete(); });
    }

    public function addNewPlace() {
        $countries = (new countries())->get();
        $categories = (new categories())->get();
        return view('/addnewplace', ['countries' => $countries, 'categories' => $categories]);
    }
    public function addPlace(request $data) {
        
    
        $validated = $data->validate([
            'image' => 'required',
            'posx' => 'required|gt:-90|lt:90',
            'posy' => 'required|gt:-180|lt:180',
            'country' => 'required',
            'category' => 'required',
            'difficulty' => 'required',
        ]);
        $path = Storage::disk('public_uploads')->put('img', $data['image']);
        $imageName = basename($path);

        places::insert([
        'image_name' => $imageName,
        'lat' => $data['posx'],
        'lng' => $data['posy'],
        'country_id' => $data['country'],
        'category_id' => $data['category'] == 'NULL' ? null : $data['category'],
        'difficulty' => $data['difficulty']
        ]);
        return redirect()->to('/categorylist')->with('message','Jauna lokacija ir izveidota!');
        // return redirect()->to('/categorylist')->with('message','New place added successfully!');
    }
    public function placelist() {
        $places = places::join('countries', 'countries.id', '=', 'places.country_id')
                        ->orderBy('places.id')
                        ->select('places.id as place_id', 'image_name', 'countries.name', 'difficulty')
                        ->get();
        return view('/placelist', ['places' => $places]);
    }
    public function deleteplace($id) {
        if (!Auth::user()->admin)
            return redirect()->to('placelist')->withErrors('You have no permission to delete this data!');

        places::where('id', '=', $id)->get()->each(function($row){ Storage::disk('public_uploads')->delete('img/'.$row->image_name); });;
        results::where('place_id', '=', $id)->delete();
        places::where('id', '=', $id)->delete();

        return redirect()->to('placelist');
    }
    public function openEditorPlace($id) {
        $countries = (new countries())->get();
        $categories = (new categories())->get();
        return view('/editplace', ['id' => $id, 'place' => (places::where('id', '=', $id)->first()), 'countries' => $countries, 'categories' => $categories]);
    }
    public function editPlace(request $data) {
        if ($data['image'])
        {
            $path = Storage::disk('public_uploads')->put('img', $data['image']);
            $imageName = basename($path);

            Storage::disk('public_uploads')->delete('img/'.(places::where('id', '=', $data['id'])->first())->image_name);

            places::where('id', '=', $data['id'])->update(['image_name' => $imageName]);    
        }
        places::where('id', '=', $data['id'])->update(['lat' => $data['posx'], 'lng' => $data['posy'], 'category_id' => $data['category'] == 'NULL' ? null : $data['category']]);
        return redirect()->to('/categorylist');
    }

    public function addCategory(request $data) {
        $validated = $data->validate([
            'name' => 'required'
        ]);

        categories::insert([
        'name' => $data['name']
        ]);
        return redirect()->to('/categorylist')->with('message','New category added successfully!');
    }
    public function categorylist() {
        $places = (new places())->get();
            // dd($places);
        $connections = (new category_place_connections())
            ->leftJoin('categories', 'categories.id', '=', 'category_place_connections.category_id')
            ->select('category_place_connections.place_id', 'categories.name')->get();
        return view('/categorylist', ['categories' => (new categories())->get(), 'places' => $places, 'connections' => $connections]);
    }
    public function deletecategory($id) {
        if (!Auth::user()->admin)
            return redirect()->to('categorylist')->withErrors('You have no permission to delete this data!');

        results::join('places', 'places.id', '=', 'results.place_id')
            ->where('category_id', '=', $id)
            ->delete();
        places::where('category_id', '=', $id)->get()->each(function($row){ Storage::disk('public_uploads')->delete('img/'.$row->image_name); });;
        places::where('category_id', '=', $id)->delete();
        categories::where('id', '=', $id)->delete();
        return redirect()->to('categorylist');
    }
    public function openEditorCategory($id) {
        return view('/editcategory', ['id' => $id, 'name' => (categories::where('id', '=', $id)->first())->name]);
    }
    public function editCategory(request $data) {
        categories::where('id', '=', $data['id'])->update(['name' => $data['name']]);
        return redirect()->to('/categorylist');
    }

    public function detachPlace($id){
        places::where('id', '=', $id)->update(['category_id' => null]);
        return response()->json(['success' => true]);
    }
    public function attachPlace($id, $category){
        places::where('id', '=', $id)->update(['category_id' => categories::where('name', '=', $category)->first()->id]);
        return response()->json(['success' => true, 'category' => $category]);
    }
    public function deletePlaceFromCard($id){
        places::where('id', '=', $id)->get()->each(function($row){ Storage::disk('public_uploads')->delete('img/'.$row->image_name); });;
        results::where('place_id', '=', $id)->delete();
        places::where('id', '=', $id)->delete();
        
        return response()->json(['success' => true]);
    }

    public function leaderboard(){
        $data['results'] = (new serie_results())->distinct()->join('users', 'users.id', '=', 'serie_results.user_id')
                                          ->orderBy('serie_results.result', 'desc')
                                          ->get();

        return view('leaderboard', ['data' => $data]);
    }
}

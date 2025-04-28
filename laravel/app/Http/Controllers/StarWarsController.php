<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StarWarsController extends Controller
{
    public function addPerson(Request $request){
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        if (empty($data['id'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID is required',
            ], 400);
        }
        
        $person = $this->getJediFromApi($data['id']);

        $jediPre = Cache::pull('jedi');
        $jedi = null;

        if ($jediPre) {
            $jedi = json_decode($jediPre, true);
        }

        if (!$jedi) {
            $this->cacheJedi([
                'jedi-master-'.$person['name'] => json_encode($person),
            ]);            
        }
        else {
            $this->cacheJedi($jedi += [
                'jedi-master-'.$person['name'] => json_encode($person),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jedi master added to cache',
            'data' => $person,
        ]);
    }

    public function getJedi(){
        $jedi = Cache::get('jedi');

        if ($jedi) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jedi masters retrieved from cache',
                'data' => json_encode($jedi),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Jedi masters found in cache',
            ]);
        }
    }

    private function getJediFromApi(string $id){
        $url = "https://swapi.info/api/people/$id";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    private function cacheJedi(array $jedi){
        var_dump($jedi);
        Cache::put('jedi', json_encode($jedi), now()->addDays(7));
    }
}

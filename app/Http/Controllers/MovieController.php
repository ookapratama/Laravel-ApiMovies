<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    
    public function fetch()
    {
        $respons = Http::withHeaders([
            'X-RapidAPI-Host' => 'moviesdatabase.p.rapidapi.com',
            'X-RapidAPI-Key' => '642ea15e99msh1a464ea216d5e37p14f5dfjsn0d063045ff56',
            'content-type' => 'application/octet-stream',
    
        ])->get('https://moviesdatabase.p.rapidapi.com/titles');

        $movies = json_decode($respons->body());

        // store data ke database
        foreach ($movies->results as $m) {
            Movie::insert([
                'title' => $m->titleText->text,
                'description' => $m->primaryImage->caption->plainText ?? 'Tidak ada Sinopsis',
                'image' => $m->primaryImage->url ?? 'Tidak ada Thumbnail',
                'releaseYear' => $m->releaseYear->year,
            ]);
        }
        // dump($movies->results[0]->titleText->text);
        // dd($movies);

        return 'Data API berhasil ditambah ke Database';
    }

    public function index() {
        $data['movies'] = Movie::all();
        return view('movie', $data);
    }

}

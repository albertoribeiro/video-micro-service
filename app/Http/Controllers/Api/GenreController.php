<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{

    public function index()
    {
        return Genre::all();
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'=> 'required|max:255',
            'is_active' => 'boolean'
        ]);


        return Genre::create($request->all());
    }

    public function show(Genre $genre)
    {
        return $genre;
    }

    public function update(Request $request,Genre $genre)
    {
         $genre->update($request->all());
         return $genre;
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->noContent();
    }

}

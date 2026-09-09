<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BikeModel;
use App\Models\NikeModel;

class BikeController extends Controller
{

    function displayView()
    {
        $nakes = NikeModel::all();
        return view('bikeView', ['nakes' => $nakes]);
    }

    function createBikeForm()
    {
        return view('createBike');
    }

    function createBike(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'required',
            'image' => 'required|url',
            'model' => 'required',
            'year' => 'required|integer|min:1885|max:' . date('Y'),
            'color' => 'required',
            'price' => 'required|numeric|min:0'
        ]);

        BikeModel::create($validatedData);

        return redirect('/bike-view');
    }

    function deleteBike($id)
    {
        $bike = BikeModel::findOrFail($id);
        $bike->delete();

        return redirect('/bike-view');
    }
}

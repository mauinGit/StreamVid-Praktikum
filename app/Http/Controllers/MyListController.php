<?php

namespace App\Http\Controllers;

use App\Models\MyList;
use App\Models\Film;
use Illuminate\Http\Request;

class MyListController extends Controller
{
    public function index()
    {
        $myList = auth()->user()->myLists()->with('film')->latest()->get();

        return view('mylist.index', compact('myList'));
    }

    public function toggle(Film $film)
    {
        $user = auth()->user();
        $existing = MyList::where('user_id', $user->id)->where('film_id', $film->id)->first();

        if ($existing) {
            $existing->delete();
            $message = 'Film dihapus dari My List';
            $added = false;
        } else {
            MyList::create([
                'user_id' => $user->id,
                'film_id' => $film->id,
            ]);
            $message = 'Film ditambahkan ke My List';
            $added = true;
        }

        if (request()->ajax()) {
            return response()->json(['message' => $message, 'added' => $added]);
        }

        return back()->with('success', $message);
    }
}

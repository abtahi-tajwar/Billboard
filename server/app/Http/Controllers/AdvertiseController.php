<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertise;
use Carbon\Carbon;

class AdvertiseController extends Controller
{
    public function all() {
        return Advertise::all();
    }
    public function create(Request $req) {

        $req->validate([
            'Title' => 'required',
            'Description' => 'required',
            // 'ExpirationDate' => 'date_equals:date|after:'.Carbon::now()
        ]);

        $imgFile = $req->file('Image');
        $imgName = time() . $imgFile->getClientOriginalExtension();
        $imgFile->move(public_path().'/images/advertises', $imgName);

        $advertise = Advertise::create([
            'Title' => $req->input('Title'),
            'Description' => $req->input('Description'),
            'Image' => $imgName,
            'ExpirationDate' => $req->input('ExpirationDate'),
            'UserId' => $req->user->id        
        ]);

        return $advertise;
    }
}

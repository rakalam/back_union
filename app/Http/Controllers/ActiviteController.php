<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;

class ActiviteController extends Controller
{
   public function select_activite(){
     $donnes = Activite::orderBy('id', 'desc')->limit(5)->get();
     return response()->json([
        "donnes"=>$donnes
     ]);
   }
}

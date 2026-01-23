<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitorSessionController extends Controller
{
    public function handle(Request $request){
        return response()->json([


            'status' => 'Visitor session handled successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorSessionController extends Controller
{
    public function handle(Request $request){
        // Lookup Visitor
        $visitor = Visitor::where('nik', $request->nik)->first();

        if (!$visitor) {
            return response()->json([
                'message' => 'Visitor not found'
            ], 404);
        }
    
    }
}

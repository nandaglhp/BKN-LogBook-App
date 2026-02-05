<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Active Visit
        $activeVisit = Visit::where('visitor_id', $visitor->id)
            ->where('status', 'active')
            ->first();

        // check-in logic
        if (!$activeVisit) {
            $visit = Visit::create([
                'visitor_id' => $visitor->id,
                'room_id' => $request->room_id,
                'check_in_at' => Carbon::now(),
                'check_out_at' => null,
                'status' => 'active'
            ]);

            // history
            $history = Visit::where('visitor_id', $visitor->id)
                ->orderBy('check_in_at')
                ->get(['id', 'check_in_at', 'check_out_at']);

                return response()->json([
                'mode' => 'checked_in',
                'message' => 'Check-in successful',
                'visit_id' => $visit->id,
                'history' => $history
            ], 200);
        }

        // check-out logic
        $activeVisit->check_out_at = Carbon::now();
        $activeVisit->status = 'checked_out';
        $activeVisit->save();

         $history = Visit::where('visitor_id', $visitor->id)
            ->orderByDesc('check_in_at')
            ->get(['id', 'check_in_at', 'check_out_at']);

        return response()->json([
            'mode' => 'checked_out',
            'message' => 'Check-out successful',
            'visit_id' => $activeVisit->id,
            'history' => $history
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\VisitService;
use Illuminate\Http\Request;

class VisitorSessionController extends Controller
{
    public function __construct(
        protected VisitService $visitService
    ) {}

    public function handle(Request $request)
    {
        $request->validate([
            'nik'     => 'required|string',
            'room_id' => 'nullable|integer|exists:rooms,id',
        ]);

        $result = $this->visitService->handleVisit(
            $request->nik,
            $request->room_id
        );

        return response()->json($result);
    }
}

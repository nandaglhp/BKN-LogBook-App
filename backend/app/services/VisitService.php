<?php

namespace App\Services;

use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VisitService{
    public function handleVisit(string $nik, ?int $roomId = null): array
    {
        $visitor = Visitor::where('nik', $nik)->first();

        if (! $visitor) {
            throw ValidationException::withMessages([
                'nik' => 'Visitor not found',
            ]);
        }

        return DB::transaction(function () use ($visitor, $roomId) {

            $activeVisit = Visit::where('visitor_id', $visitor->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            // CHECK-IN
            if (! $activeVisit) {

                if (! $roomId) {
                    throw ValidationException::withMessages([
                        'room_id' => 'room_id is required for check-in',
                    ]);
                }

                $visit = Visit::create([
                    'visitor_id'  => $visitor->id,
                    'room_id'     => $roomId,
                    'check_in_at' => now(),
                    'status'      => 'active',
                ]);

                return [
                    'mode'     => 'checked_in',
                    'message'  => 'Check-in successful',
                    'visit_id' => $visit->id,
                    'history'  => $this->history($visitor->id),
                ];
            }

            // CHECK-OUT
            $activeVisit->update([
                'check_out_at' => now(),
                'status'       => 'checked_out',
            ]);

            return [
                'mode'     => 'checked_out',
                'message'  => 'Check-out successful',
                'visit_id' => $activeVisit->id,
                'history'  => $this->history($visitor->id),
            ];
        });
    }

    private function history(int $visitorId)
    {
        return Visit::where('visitor_id', $visitorId)
            ->orderByDesc('check_in_at')
            ->get(['id', 'check_in_at', 'check_out_at']);
    }
}

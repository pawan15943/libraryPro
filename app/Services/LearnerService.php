<?php

namespace App\Services;

use App\Models\CustomerDetail;
use App\Models\Hour;
use App\Models\LearnerDetail;
use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LearnerService
{
    public function getRenewalStatus($customerId)
    {
        $today = Carbon::today()->format('Y-m-d');
        $futureDate = Carbon::today()->addDays(6)->format('Y-m-d');
        
        return LearnerDetail::where('library_id', auth()->user()->id)->where('learner_id', $customerId)
            ->whereBetween('plan_start_date', [$today, $futureDate])
            ->exists() ? 1 : 0;
    }

    public function getAvailableSeats()
    {
        $firstRecord = Hour::where('library_id', auth()->user()->id)->first();
        $totalHour = $firstRecord ? $firstRecord->hour : null;
        
        return Seat::where('total_hours', '!=', $totalHour)
            ->where('library_id', auth()->user()->id)->pluck('seat_no', 'id');
    }

    public function getPlans()
    {
        return Plan::where('library_id', auth()->user()->id)->get();
    }

    public function getPlanTypes()
    {
        return PlanType::where('library_id', auth()->user()->id)->get();
    }
}

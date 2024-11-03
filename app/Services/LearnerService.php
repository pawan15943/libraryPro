<?php

namespace App\Services;

use App\Models\CustomerDetail;
use App\Models\Hour;
use App\Models\Learner;
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

    public function getAvailableSeatsPlantype()
    {
        // Step 1: Get the total allowable hours for the current user’s library
        $firstRecord = Hour::where('library_id', auth()->user()->id)->first();
        $totalHour = $firstRecord ? $firstRecord->hour : null;
    
        // Step 2: Retrieve all seats that don’t have fully booked hours
        $availableSeats = Seat::where('total_hours', '!=', $totalHour)->pluck('seat_no', 'id');
    
        // Initialize an array to hold seat numbers and their available plan types
        $seatPlanTypes = [];
    
        foreach ($availableSeats as $seatId => $seatNo) {
            // Step 3: Retrieve all bookings for the given seat
            $bookings = Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
                ->join('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')
                ->where('learners.library_id', auth()->user()->id)
                ->where('learner_detail.seat_id', $seatId)
                ->where('learners.status', 1)
                ->where('learner_detail.status', 1)
                ->get(['learner_detail.plan_type_id', 'plan_types.start_time', 'plan_types.end_time', 'plan_types.slot_hours']);
    
            // Step 4: Retrieve all plan types
            $planTypes = PlanType::all();
    
            // Step 5: Initialize an array to store the plan_type_ids to be removed
            $planTypesRemovals = [];
    
            // Step 6: Calculate total booked hours for the seat
            $totalBookedHours = $bookings->sum('slot_hours');
    
            // Step 7: Determine conflicts based on plan_type_id and hours
            foreach ($bookings as $booking) {
                foreach ($planTypes as $planType) {
                    if ($booking->start_time < $planType->end_time && $booking->end_time > $planType->start_time) {
                        $planTypesRemovals[] = $planType->id;
                    }
                }
            }
    
            // Remove duplicate entries in planTypesRemovals
            $planTypesRemovals = array_unique($planTypesRemovals);
    
            // Step 8: If total booked hours >= total allowable hours, all plan types should be removed
            if ($totalBookedHours >= $totalHour) {
                $planTypesRemovals = $planTypes->pluck('id')->toArray();
            }
    
            // Step 9: Filter out the available plan types for the seat
            $availablePlanTypes = $planTypes->filter(function ($planType) use ($planTypesRemovals) {
                return !in_array($planType->id, $planTypesRemovals);
            })->map(function ($planType) {
                return ['id' => $planType->id, 'name' => $planType->name];
            })->values();
    
            // Step 10: Add the seat number and its available plan types to the array
            $seatPlanTypes[] = [
                'seat_no' => $seatNo,
                'seat_id' => $seatId,
                'available_plan_types' => $availablePlanTypes
            ];
        }
    
        // Return the seat numbers along with their available plan types as an array
        return $seatPlanTypes;
    }
    
    
}

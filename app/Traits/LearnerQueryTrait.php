<?php

namespace App\Traits;

use App\Models\Learner;

trait LearnerQueryTrait
{
    public function getLearnersByLibrary()
    {
        return Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
                      ->where('learners.library_id', auth()->user()->id);
    }

    public function getAllLearnersByLibrary()
    {
        return Learner::leftJoin('learner_detail', 'learner_detail.learner_id', '=', 'learners.id')
                        ->leftJoin('seats', 'learner_detail.seat_id', '=', 'seats.id')
                        ->leftJoin('plans', 'learner_detail.plan_id', '=', 'plans.id')
                        ->leftJoin('plan_types', 'learner_detail.plan_type_id', '=', 'plan_types.id')
                        ->where('learners.library_id', auth()->user()->id);
    }
    
}

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
        return Learner::where('library_id', auth()->user()->id) 
                ->with([
                    'learnerDetails' => function($query) {
                        $query->with(['seat', 'plan', 'planType']);
                    }
                ]);
    }
    
}

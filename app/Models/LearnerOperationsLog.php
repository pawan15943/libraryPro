<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerOperationsLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'learner_operations_log';
    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
    }
}

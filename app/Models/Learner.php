<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\LibraryScope;

class Learner extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function planType()
    {
        return $this->belongsTo(PlanType::class, 'plan_type_id', 'id')
                    ->where('library_id', auth()->user()->id);  // PlanType specific to library
    }

    
}

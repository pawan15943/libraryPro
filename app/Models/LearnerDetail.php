<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\LibraryScope;
class LearnerDetail extends Model
{
    use HasFactory;
    protected $guarded = []; 
    protected $table = 'learner_detail';
    protected static function booted()
    {
        
        static::addGlobalScope(new LibraryScope());
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function planType()
    {
        return $this->belongsTo(PlanType::class, 'plan_type_id');
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }
    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }
}

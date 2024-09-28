<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\LibraryScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Learner extends Authenticatable
{
    use HasRoles;
    use HasFactory;
    protected $guarded = [];
    
    public function planType()
    {
        return $this->belongsTo(PlanType::class, 'plan_type_id', 'id')
                    ->where('library_id', auth()->user()->id);  // PlanType specific to library
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id')
                    ->where('library_id', auth()->user()->id); 
    }

    
}

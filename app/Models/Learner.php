<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\LibraryScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class Learner extends Authenticatable
{
    use HasRoles;
    use HasFactory,SoftDeletes;
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

    public function learnerDetails()
    {
        return $this->hasMany(LearnerDetail::class);
    }

    public function learnerTransactions()
    {
        return $this->hasMany(LearnerTransaction::class);
    }

    public function getEmailAttribute($value)
    {
        return decryptData($value);
    }

    public function getMobileAttribute($value)
    {
        return decryptData($value);
    }
}

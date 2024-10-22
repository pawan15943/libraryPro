<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\LibraryScope;
class LearnerTransaction extends Model
{
    use HasFactory;
    protected $table = 'learner_transactions';
    protected $guarded = [];
    protected static function booted()
    {
        
        static::addGlobalScope(new LibraryScope());
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
    }
   
}

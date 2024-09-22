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
}

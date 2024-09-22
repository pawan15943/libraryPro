<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\LibraryScope;
class Hour extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'hour';
    protected static function booted()
    {
        
        static::addGlobalScope(new LibraryScope());
    }
}

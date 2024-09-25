<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Scopes\LibraryScope;

class Library extends Authenticatable implements MustVerifyEmail
{
    use HasFactory , Notifiable;
    use HasRoles;
    protected $guard = 'library';
    protected $guarded = []; 
   
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];
    
    public function library_transactions()
    {
        return $this->hasMany(LibraryTransaction::class, 'library_id', 'id'); 
        // Adjust the foreign key and local key if necessary
    }


  
    
}

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
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'library_type', 'id'); // Assuming 'library_type' in libraries matches 'id' in subscriptions
    }
    
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // Library belongs to a City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

  
    
}

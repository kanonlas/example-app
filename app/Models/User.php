<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use  Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 
        'name',
        'email',
        'password',
        'birthdate',
        'profile_photo',
        'type_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bio(): HasOne
    { 
        return $this->hasOne(UserBio::class, 'user_id'); 
    }

    public function diaryEntries()
    { 
    return $this->hasMany(DiaryEntry::class); 
    }

    // A user belongs to one PersonalityType
    public function personality_types(): BelongsTo
    {
        return $this->belongsTo(PersonalityType::class, 'type_id');
    }

    public function edit()
{
    $user = Auth::user()->load('personality_types');
    return view('profile.edit', compact('user'));
}


}

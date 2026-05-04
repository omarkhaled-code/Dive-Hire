<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
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

    public function developerProfile()
    {
        return $this->hasOne(DeveloperProfile::class);
    }
    
    public function employerProfile()
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'employer_id');
    }

    public function develoeprApplications()
    {
        return $this->hasMany(Application::class, 'developer_id');
    }
    


    public function hasProfile()
    {
        if ($this->role === 'developer') {
            
            return $this->developerProfile()->exists();
        }

        if ($this->role === 'employer') {
            return $this->employerProfile()->exists();
        }

        return false;
    }
}

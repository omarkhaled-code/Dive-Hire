<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'phone', 'avatar', 'company_name', 'company_logo', 'company_website', 'company_size', 'company_location', 'company_bio'])]
class EmployerProfile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }



//     public function applications()
//     {
//         return $this->hasManyThrough(Application::class, Listing::class);
//     }

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['employer_id', 'title', 'description', 'location', 'experience_years', 'salary_min', 'salary_max', 'type', 'status'])]
class Listing extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}

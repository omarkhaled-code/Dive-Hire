<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable('developer_profile_id', 'title', 'description', 'url', 'image')]
class Project extends Model
{
    
    public function developerProfile()
    {
        return $this->belongsTo(DeveloperProfile::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skill', 'project_id', 'skill_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Skill extends Model
{
    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'listing_skill');
    }

    public function developerProfiles()
    {
        return $this->belongsToMany(DeveloperProfile::class, 'developer_skill');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skill');
    }
}

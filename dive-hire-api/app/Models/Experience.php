<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['developer_profile_id', 'company', 'role', 'location', 'start_date', 'end_date', 'description'])]
class Experience extends Model
{
    public function developerProfile()
    {
        return $this->belongsTo(DeveloperProfile::class);
    }
}

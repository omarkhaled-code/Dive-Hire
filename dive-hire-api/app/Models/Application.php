<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['developer_id', 'listing_id', 'cover_letter', 'status'])]
class Application extends Model
{
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}

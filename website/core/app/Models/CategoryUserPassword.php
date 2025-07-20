<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryUserPassword extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ChannelCategory::class, 'category_id');
    }
}

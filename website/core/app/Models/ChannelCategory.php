<?php

namespace App\Models;

use App\Traits\ApiQuery;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class ChannelCategory extends Model {
    use GlobalStatus, ApiQuery;
    
    protected $casts = [
        'is_adult' => 'boolean',
    ];
    public function channels() {
        return $this->belongsToMany(
            LiveTelevision::class,
            'channel_category_live_television',
            'channel_category_id',
            'live_television_id'
        );
    }

    public function liveTelevisions() {
        return $this->channels();
    }
}

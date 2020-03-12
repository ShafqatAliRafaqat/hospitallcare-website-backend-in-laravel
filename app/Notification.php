<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded  = ['id'];

    public function users(){
        return $this->belongsToMany('App\User','user_notification','notification_id','user_id')
            ->withPivot('isRead');
    }
}

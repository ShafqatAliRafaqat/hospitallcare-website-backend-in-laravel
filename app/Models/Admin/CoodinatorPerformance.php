<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class CoodinatorPerformance extends Model
{
    protected $guarded  =   ['id'];
    protected $table    =   'coordinator_performance';

    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}

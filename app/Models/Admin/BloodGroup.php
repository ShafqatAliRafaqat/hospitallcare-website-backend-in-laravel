<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BloodGroup extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'blood_groups';

    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}

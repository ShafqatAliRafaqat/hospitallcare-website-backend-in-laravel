<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Status extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'status';

    public function customer()
    {
    	return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}

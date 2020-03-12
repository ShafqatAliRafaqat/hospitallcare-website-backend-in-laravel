<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalClaims extends Model
{
    use SoftDeletes;
    protected $table    =   'customer_claims';
    protected $guarded       =   ['id'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerRiskFactor extends Model
{
    use SoftDeletes;
    protected $guarded  =   ['id'];
    protected $table    =   'customer_risk_factors';

    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Procedure extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function treatment()
    {
    	return $this->belongsTo('App\Models\Admin\Treatment');
    }
    public function customer()
    {
        return $this->belongsToMany('App\Models\Admin\Customer','customer_procedures','customer_id','treatment_id')
        ->withPivot(['treatment_id', 'customer_id', 'hospital_id','cost','discounted_cost','discount_per','appointment_date'])->withTimestamps();
    }
    public function customer_history(){
        return $this->belongsToMany('App\Models\Admin\Customer', 'customer_history', 'customer_id','treatment_id')
        ->withPivot([ 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date'])->withTimestamps();
     }
}

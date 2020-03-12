<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Lab extends Model
{
    use SoftDeletes;
    protected $table = 'labs';
    protected $guarded = ['id'];

    public function diagnostic(){
        return $this->belongsToMany('App\Models\Admin\Diagnostics','lab_diagnostics','lab_id','diagnostic_id')
        ->withPivot(['lab_id','diagnostic_id','cost'])->withTimeStamps();
    }
    public function customer(){
        return $this->belongsToMany('App\Models\Admin\Customer','customer_diagnostics','customer_id','lab_id')
       ->withPivot([ 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date'])->withTimestamps();
    }
    public function customer_history(){
        return $this->belongsToMany('App\Models\Admin\Customer', 'customer_treatment_history', 'customer_id','lab_id')
        ->withPivot([ 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date'])->withTimestamps();
     }
    public function customer_diagnostic_lab(){
        return $this->belongsToMany('App\Models\Admin\Diagnostics','customer_diagnostics','diagnostic_id','lab_id')
       ->withPivot([ 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date'])->withTimestamps();
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Diagnostics extends Model
{
    use SoftDeletes;
    protected $table = 'diagnostics';
    protected $guarded = ['id'];

    public function lab(){
        return $this->belongsToMany('App\Models\Admin\Lab','lab_diagnostics','lab_id','diagnostic_id')
        ->withPivot(['lab_id','diagnostic_id','cost'])->withTimestamps();
    }
    public function customer(){
        return $this->belongsToMany('App\Models\Admin\Customer','customer_diagnostics','diagnostic_id','customer_id')
       ->withPivot([ 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function customer_lab_diagnostic(){
        return $this->belongsToMany('App\Models\Admin\Lab','customer_diagnostics','lab_id','diagnostic_id')
       ->withPivot([ 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
}

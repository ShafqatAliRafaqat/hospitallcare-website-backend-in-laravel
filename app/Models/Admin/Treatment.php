<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
use SoftDeletes;
    protected $guarded = ['id'];
    public function procedures()
    {
    	return $this->belongsTo(Treatment::class, 'parent_id');
    }

    public function hasProcedures()
    {
        return $this->hasMany(Treatment::class,'parent_id','id');
    }
    public function customer()
    {
        return $this->belongsToMany('App\Models\Admin\Customer','customer_procedures','customer_id','treatments_id')
        ->withPivot(['treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function customer_history(){
        return $this->belongsToMany('App\Models\Admin\Customer', 'customer_treatment_history', 'customer_id','treatments_id')
        ->withPivot([ 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
     }
    public function doctor()
    {
        return $this->belongsToMany('App\Models\Admin\Doctor','doctor_treatments','treatment_id','doctor_id')
        ->withPivot(['treatment_id', 'doctor_id','schedule_id','cost'])->withTimestamps();
    }
   public function center()
    {
        return $this->belongsToMany('App\Models\Admin\Center','customer_procedures','hospital_id','treatments_id')
        ->withPivot(['treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function treatment_center(){
        return $this->belongsToMany('App\Models\Admin\Center', 'center_treatments','med_centers_id', 'treatments_id')
        ->withPivot([ 'treatments_id', 'med_centers_id','cost'])->withTimestamps();
    }
    public function treatment_image()
    {
        return $this->hasMany('App\Models\Admin\TreatmentImages','treatment_id','id');
    }

}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use SoftDeletes;
    protected $table    =   'customers';
    protected $guarded  =   ['id'];

    public function center(){
      return $this->belongsToMany('App\Models\Admin\Center', 'customer_procedures','customer_id', 'hospital_id')
      ->withPivot([ 'id','customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','status','appointment_date','appointment_from'])->withTimestamps();
   }
   public function center_history(){
    return $this->belongsToMany('App\Models\Admin\Center', 'customer_treatment_history','customer_id', 'hospital_id')
    ->withPivot(['id', 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function User()
    {
        return $this->hasMany('App\User');
    }
   public function Status()
   {
       return $this->hasOne('App\Models\Admin\Status')->withTimestamps();
   }
    public function customer_doctor_notes()
   {
       return $this->hasOne('App\Models\Admin\CustomerDoctorNotes')->withTimestamps();
   }
    public function customer_allergy()
   {
       return $this->hasOne('App\Models\Admin\CustomerAllergy')->withTimestamps();
   }
    public function customer_images()
   {
       return $this->hasOne('App\Models\Admin\CustomerImages');
   }
    public function coordinator_performance()
   {
       return $this->hasOne('App\Models\Admin\CoordinatorPerformance')->withTimestamps();
   }
    public function customer_risk_factor()
   {
       return $this->hasOne('App\Models\Admin\CustomerRiskFactor')->withTimestamps();
   }
    public function blood_group()
   {
       return $this->hasOne('App\Models\Admin\BloodGroup')->withTimestamps();
   }
   public function treatments()
    {
        return $this->belongsToMany('App\Models\Admin\Treatment','customer_procedures','customer_id','treatments_id')
        ->withPivot([ 'id','treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','status','appointment_date','appointment_from'])->withTimestamps();
    }
    public function treatments_history()
    {
        return $this->belongsToMany('App\Models\Admin\Treatment','customer_treatment_history','customer_id','treatments_id')
        ->withPivot(['id','treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function procedures()
    {
        return $this->belongsToMany('App\Models\Admin\Procedure','customer_procedures','customer_id','hospital_id')
        ->withPivot(['id','treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','status','appointment_date','appointment_from'])->withTimestamps();
    }
    public function procedures_history()
    {
        return $this->belongsToMany('App\Models\Admin\Procedure','customer_treatment_history','customer_id','hospital_id')
        ->withPivot(['id','treatments_id', 'customer_id', 'hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function doctor()
    {
     return $this->belongsToMany('App\Models\Admin\Doctor', 'customer_procedures','customer_id', 'doctor_id')
       ->withPivot(['id', 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','status','appointment_date','appointment_from'])->withTimestamps();
    }
    public function doctor_history()
    {
     return $this->belongsToMany('App\Models\Admin\Doctor', 'customer_treatment_history','customer_id', 'doctor_id')
       ->withPivot([ 'id','customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function diagnostics()
    {
     return $this->belongsToMany('App\Models\Admin\Diagnostics', 'customer_diagnostics', 'customer_id','diagnostic_id')
       ->withPivot(['id', 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function diagnostics_history()
    {
     return $this->belongsToMany('App\Models\Admin\Diagnostics', 'customer_diagnostic_history', 'customer_id','diagnostic_id')
       ->withPivot(['id','customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function labs()
    {
     return $this->belongsToMany('App\Models\Admin\Lab', 'customer_diagnostics','customer_id', 'lab_id')
       ->withPivot(['id', 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function labs_history()
    {
     return $this->belongsToMany('App\Models\Admin\Lab', 'customer_diagnostic_history','customer_id', 'lab_id')
       ->withPivot(['id', 'customer_id', 'diagnostic_id','lab_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function medical_claims()
    {
      return $this->hasMany('App\Models\Admin\MedicalClaims');
    }
}

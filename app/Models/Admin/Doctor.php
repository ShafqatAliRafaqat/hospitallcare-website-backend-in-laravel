<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Doctor extends Model
{
    use SoftDeletes;
    protected $table = 'doctors';
    protected $guarded = ['id'];
    public function User()
    {
        return $this->hasMany('App\User');
    }
    public function treatments()
    {
        return $this->belongsToMany('App\Models\Admin\Treatment','doctor_treatments','doctor_id','treatment_id')
        ->withPivot(['treatment_id', 'doctor_id','schedule_id','cost'])->withTimestamps();
    }
    public function centers()
    {
        return $this->belongsToMany('App\Models\Admin\Center','center_doctor_schedule','doctor_id','center_id')
        ->withPivot(['center_id', 'doctor_id','time_from','time_to','day_from','day_to','fare','discount','is_primary','id'])->withTimestamps();
    }
    public function doctor_image()
    {
        return $this->hasOne('App\Models\Admin\DoctorImage','doctor_id','id');
    }
    public function doctor_qualification()
    {
        return $this->hasOne('App\Models\Admin\DoctorQualification','doctor_id','id');
    }
    public function doctor_certification()
    {
        return $this->hasOne('App\Models\Admin\DoctorCertification','doctor_id','id');
    }
    public function customers()
    {
        return $this->belongsToMany('App\Models\Admin\Customer','customer_procedures','doctor_id','customer_id')
        ->withPivot(['customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function customer_history(){
        return $this->belongsToMany('App\Models\Admin\Customer', 'customer_treatment_history', 'doctor_id','customer_id')
        ->withPivot([ 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
     }
    public function doctor_partnership_images(){
        return $this->hasMany('App\Models\Admin\DoctorPartnershipImages','doctor_id','id');
    }
    public function doctor_partnership_files(){
        return $this->hasMany('App\Models\Admin\DoctorPartnershipFiles','doctor_id','id');
    }
}

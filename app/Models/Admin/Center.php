<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Center extends Model
{
    use SoftDeletes;
    protected $table = 'medical_centers';
    protected $guarded = ['id'];
    public function customer(){
       return $this->belongsToMany('App\Models\Admin\Customer', 'customer_procedures', 'hospital_id','customer_id')
       ->withPivot(['id', 'customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function customer_history(){
        return $this->belongsToMany('App\Models\Admin\Customer', 'customer_treatment_history', 'hospital_id','customer_id')
        ->withPivot([ 'id','customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
     }
   public function treatment(){
        return $this->belongsToMany('App\Models\Admin\Treatment', 'customer_procedures', 'hospital_id','treatments_id')
        ->withPivot([ 'id','customer_id', 'treatments_id','hospital_id','doctor_id','cost','discounted_cost','discount_per','appointment_date','appointment_from'])->withTimestamps();
    }
    public function center_treatment(){
        return $this->belongsToMany('App\Models\Admin\Treatment', 'center_treatments','med_centers_id', 'treatments_id')
        ->withPivot([ 'id','treatments_id', 'med_centers_id','cost'])->withTimestamps();
    }
    public function doctor(){
        return $this->belongsToMany('App\Models\Admin\Doctor', 'center_doctor_schedule','doctor_id', 'center_id')
        ->withPivot(['id','center_id', 'doctor_id','time_from','time_to','day_from','day_to','fare','discount','id'])->withTimestamps();
    }
   public function User()
    {
        return $this->hasMany('App\User');
    }
    public function center_image(){
        return $this->hasOne('App\Models\Admin\CenterImage','center_id','id');
    }
    public function center_partnership_images(){
        return $this->hasMany('App\Models\Admin\CenterPartnershipImages','center_id','id');
    }
    public function center_partnership_files(){
        return $this->hasMany('App\Models\Admin\CenterPartnershipFiles','center_id','id');
    }
}

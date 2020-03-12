<?php

namespace App;

use App\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table    =   'users';
    protected $guarded  = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function Organization()
    {
        return $this->belongsTo('App\Organization','organization_id');
    }
    public function Center()
    {
        return $this->belongsTo('App\Models\Admin\Center','medical_center_id');
    }
    public function Doctor()
    {
        return $this->belongsTo('App\Models\Admin\Doctor','doctor_id');
    }
    public function Customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer','customer_id');
    }
    public function Role(){
        return $this->belongsToMany('App\Role');
    }
    public function notifications(){
        return $this->belongsToMany('App\Notification','user_notification','user_id','notification_id')
        ->withPivot('isRead');
    }
    public function UserImage()
    {
        return $this->hasOne('App\Models\Admin\UserImages','user_id','id');
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TempCustomer extends Model
{
    protected $table = 'temp_customers';
    protected $guarded = ['id'];
    
    public function customer_notes()
    {
        return $this->hasMany('App\Models\Admin\TempNotes','customer_id','id');
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Blog extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'blogs';
    public function blog_images(){
        return $this->hasMany('App\Models\Admin\BlogImage','blog_id','id');
    }
   public function blog_category()
   {
       return $this->belongsTo('App\Models\Admin\BlogCategory','category_id');
   }
}

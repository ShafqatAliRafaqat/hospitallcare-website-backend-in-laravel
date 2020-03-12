<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $guarded = ['id'];
    protected $table = 'blog_images';
    public function Blog(){
        return $this->belongsTo('App\Models\Admin\Blog');
    }
}

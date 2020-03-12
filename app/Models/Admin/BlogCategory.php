<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'blog_category';
    
    public function Blogs()
    {
        return $this->hasMany('App\Models\Admin\Blog');
    }
}

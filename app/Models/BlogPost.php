<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model {
    protected $fillable = ['title','slug','excerpt','body','category','image','published','read_time'];
    protected $casts    = ['published' => 'boolean'];
}

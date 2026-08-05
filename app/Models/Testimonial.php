<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {
    protected $fillable = ['name','position','company','message','rating','avatar','active','sort_order'];
    protected $casts    = ['active' => 'boolean'];
}
